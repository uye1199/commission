<?php
namespace Paysera\Models;

class Currency
{
    private $currency;
    private $conversion_rate;

    /**
     * Currency constructor.
     * @param $currency
     * @param $conversion_rate
     */
    public function __construct($currency, $conversion_rate)
    {
        $this->currency = $currency;
        $this->conversion_rate = $conversion_rate;
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
        return $this->conversion_rate;
    }

    /**
     * @param mixed $conversion_rate
     */
    public function setConversionRate($conversion_rate)
    {
        $this->conversion_rate = $conversion_rate;
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
