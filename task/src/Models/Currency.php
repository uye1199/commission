<?php
namespace Paysera\Models;

class Currency
{
    private $currency;
    private $conversionRate;
    private $precision;

    /**
     * Currency constructor.
     * @param $currency
     * @param $conversionRate
     * @param $precision
     */
    public function __construct($currency, $conversionRate, $precision)
    {
        $this->setCurrency($currency);
        $this->setConversionRate($conversionRate);
        $this->setPrecision($precision);
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
     * @return mixed
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param mixed $precision
     */
    public function setPrecision($precision)
    {
        $this->precision = $precision;
    }

    /**
     * @param $amount
     * @return float|int
     */
    public function convertToEuro($amount)
    {
        if ($this->getCurrency() !== 'EUR') {
            return $amount / $this->getConversionRate();
        }

        return $amount;
    }

    /**
     * @param $amount
     * @return float|string
     */
    public function format($amount)
    {
        if ($this->getPrecision() === 0) {
            return ceil($amount);
        } else {
            return number_format(round($amount, $this->getPrecision() + 1), $this->getPrecision());
        }
    }
}
