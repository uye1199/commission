<?php
namespace Paysera\Models;

class Currency
{
    private $currency;
    private $conversionRate;

    /**
     * Currency constructor.
     * @param $currency
     * @param $conversionRate
     */
    public function __construct($currency, $conversionRate)
    {
        $this->currency = $currency;
        $this->conversionRate = $conversionRate;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getConversionRate()
    {
        return $this->conversionRate;
    }

    /**
     * @param mixed $conversionRate
     */
    public function setConversionRate($conversionRate)
    {
        $this->conversionRate = $conversionRate;
    }

    /**
     * @param $amount
     * @return float|int
     */
    public function convert($amount)
    {
        return $amount / $this->getConversionRate();
    }

    /**
     * @param $amount
     * @return float|string
     */
    public function format($amount)
    {
        if ($this->getCurrency() === 'JPY') {

            return ceil($amount);
        } else {

            return number_format(round($amount, 3), 2);
        }
    }
}
