<?php
namespace Paysera\Services;


use Paysera\Models\Currency;
use Paysera\Models\Operation;
use Paysera\Models\User;

class Commission
{
    private $commissions = [];
    private $operations;
    private $cashInCommission = 0.03 / 100.00;
    private $cashInMaxLimit = 5;
    private $cashOutLegalCommission = 0.3 / 100.00;
    private $cashOutLegalMin = 0.5;
    private $cashOutNaturalCommission = 0.3 / 100.00;
    private $weeklyNaturalFreeAmount = 1000;
    private $weeklyNaturalFreeoperations = 3;
    private $currency;

    /**
     * Commission constructor.
     * @param $operations
     */
    public function __construct(array $operations)
    {
        $this->operations = $operations;
    }

    /**
     * @param $currency
     * @return Currency
     */
    private function getOperationCurrency($currency)
    {
        return $this->currency[$currency];
    }

    /**
     * For every user operation calculate the commission fee and push it to commissions
     *
     * @return array
     */
    public function process()
    {
        foreach ($this->operations as $key => $operation)
        {
            $commission = 0;

            switch ($operation->getType()){
                case 'cash_in':
                    $commission = $this->calculateCashIn($operation);
                    break;
                case 'cash_out':
                    $commission = $this->calculateCashOut($operation);
                    break;
            }

            array_push($this->commissions, $commission);
        }

        return $this->commissions;
    }

    /**
     * Calculate Cash in commissions
     *
     * @param Operation $operation
     * @return mixed
     */
    private function calculateCashIn(Operation $operation)
    {
        $currency = $operation->getCurrency();
        $commission = $operation->getAmount() * $this->cashInCommission;

        if ($commission > $this->cashInMaxLimit) {
            $commission = $this->cashInMaxLimit;
        }

        return $operation->getCurrency()->format($commission);
    }

    /**
     * Calculate cashout commissions according to operation type
     *
     * @param Operation $operation
     * @return int
     */
    private function calculateCashOut(Operation $operation)
    {
        $userType = $operation->getUser()->getUserType();
        $commission = 0;

        if ($userType === User::TYPE_LEGAL) {
            $commission = $this->calculateLegalCashOut($operation);
        } else if ($userType === User::TYPE_NATURAL) {
            $commission = $this->calculateNaturalCashOut($operation);
        }

        return $commission;
    }

    /**
     * Calculate cash out commission for natural user
     *
     * @param Operation $operation
     * @return mixed
     */
    private function calculateNaturalCashOut(Operation $operation)
    {
        $user = $operation->getUser();
        $week = $operation->getWeek();
        $amount = $operation->getAmount();
        $convertedAmount = $operation->getCurrency()->convertToEuro($amount);
        $commission = 0;
        $amountOfUserOperationsForWeek = $user->getAmoutOfUserOperationsForWeek($week);

        if ($amountOfUserOperationsForWeek['count'] <= $this->weeklyNaturalFreeoperations &&
            $amountOfUserOperationsForWeek['amount'] < $this->weeklyNaturalFreeAmount
        ) {
            $freeAmount = $this->weeklyNaturalFreeAmount - $amountOfUserOperationsForWeek['amount'];
            $calculateAmount = $convertedAmount - $freeAmount;

            if ($calculateAmount > 0) {
                $commission  = ($calculateAmount * $operation->getCurrency()->getConversionRate()) * $this->cashOutNaturalCommission;
            }
        } else {
            $commission = $amount * $this->cashOutNaturalCommission;
        }

        $user->setWeeklyUserOperationAmount($week, $convertedAmount);

        return $operation->getCurrency()->format($commission);
    }

    /**
     * Calculate cash out commission for legal user
     *
     * @param Operation $operation
     * @return mixed
     */
    private function calculateLegalCashOut(Operation $operation)
    {
        $currency = $operation->getCurrency();
        $commission     = $operation->getAmount() * $this->cashOutLegalCommission;
        $converted = $operation->getCurrency()->convertToEuro($operation->getAmount()) * $this->cashOutLegalCommission;

        if ($converted < $this->cashOutLegalMin) {
            $commission = $this->cashOutLegalMin * $operation->getCurrency()->getConversionRate();
        }

        return $operation->getCurrency()->format($commission);
    }
}
