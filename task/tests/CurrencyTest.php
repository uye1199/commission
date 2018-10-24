<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paysera\Config\Config;
use \Paysera\Models\Currency;

class CurrencyTest extends TestCase
{
    public $currencies;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $config = new Config();
        $this->currencies = $config->getCurrencies();
    }

    public function testEuroConversion()
    {
        $euro = new Currency('EUR', 1.0, 2);


        $this->assertEquals(1000, $euro->convertToEuro(1000));
    }

    public function testUsdConversion()
    {
        $usd = new Currency('USD', 1.1497, 2);

        $this->assertEquals(869.79, number_format($usd->convertToEuro(1000), 2));
    }
}
