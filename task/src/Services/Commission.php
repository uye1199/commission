<?php
namespace Paysera\Services;


use Paysera\Models\Currency;
use Paysera\Models\Operation;
use Paysera\Models\User;

class Commission
{
    public $commissions = [];
    public static $users = [];
    private $operations;

    private $cashInCommission = 0.03 / 100.00;
    private $cashInMaxLimit = 5;

    private $cashOutLegalCommission = 0.3 / 100.00;
    private $cashOutLegalMin = 0.5;

    private $cashOutNaturalCommission = 0.3 / 100.00;
    private $weeklyNaturalFreeAmount = 1000;
    private $weeklyNaturalFreeoperations = 3;


    /**
     * Commission constructor.
     * @param $operations
     */
    public function __construct(array $operations)
    {
        $this->operations = $operations;
        $this->usd = new Currency('USD', 1.1497);
        $this->jpy = new Currency('JPY', 129.53);
        $this->eur = new Currency('EUR', 1);
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
            $method = 'calculate' . ucfirst(str_replace('_', '', ucwords($operation->getType(), '_')));
            $commission = $this->$method($operation);

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
        $currency = strtolower($operation->getCurrency());
        $commission = $operation->getAmount() * $this->cashInCommission;

        if ($commission > $this->cashInMaxLimit) {
            $commission = $this->cashInMaxLimit;
        }

        return $this->$currency->format($commission);
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

        if ($userType === User::$TYPE_LEGAL) {
            $commission = $this->calculateLegalCashOut($operation);
        } else if ($userType === User::$TYPE_NATURAL) {
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
        $date = $operation->getDate();
        $year = $date->format("Y");
        $month = $date->format("m");
        $week = $operation->getWeek();
        $amount = $operation->getAmount();
        $currency = strtolower($operation->getCurrency());
        $convertedAmount = $this->$currency->convert($amount);
        $commission = 0;
        $amoutOfUserOperationsforWeek = User::getAmoutOfUserOperationsforWeek($user, $year, $month, $week);

        if ($amoutOfUserOperationsforWeek['count'] <= $this->weeklyNaturalFreeoperations &&
            $amoutOfUserOperationsforWeek['amount'] < $this->weeklyNaturalFreeAmount) {

            $freeAmount = $this->weeklyNaturalFreeAmount - $amoutOfUserOperationsforWeek['amount'];

            $calculateAmount = $convertedAmount - $freeAmount;
            if ($calculateAmount > 0) {
                $commission  = ($calculateAmount * $this->$currency->getConversionRate()) * $this->cashOutNaturalCommission;
            }
        } else {
            $commission = $amount * $this->cashOutNaturalCommission;
        }

        User::setUserOperations($user, $year, $month, $week, $convertedAmount);

        return $this->$currency->format($commission);
    }

    /**
     * Calculate cash out commission for legal user
     *
     * @param Operation $operation
     * @return mixed
     */
    private function calculateLegalCashOut(Operation $operation)
    {
        $currency = strtolower($operation->getCurrency());
        $commission     = $operation->getAmount() * $this->cashOutLegalCommission;
        $converted = $this->$currency->convert($operation->getAmount()) * $this->cashOutLegalCommission;


        if ($converted < $this->cashOutLegalMin) {
            $commission = $this->cashOutLegalMin * $operation->getCurrency()->getConversionRate();
        }

        return $this->$currency->format($commission);
    }
}
