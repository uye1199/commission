<?php
namespace Paysera\Services;

use Paysera\Models\Currency;

class Config
{
    private $currencies;

    /**
     * Config constructor.
     * @param $currencies
     */
    public function __construct()
    {
        $this->currencies = [
            'USD' => new Currency('USD', 1.1497, 2),
            'JPY' => new Currency('JPY', 129.53, 0),
            'EUR' => new Currency('EUR', 1.0, 2)
        ];
    }

    /**
     * @return array
     */
    public function getCurrencies()
    {
        return $this->currencies;
    }

    /**
     * @param array $currencies
     */
    public function setCurrencies($currencies)
    {
        $this->currencies = $currencies;
    }


}

