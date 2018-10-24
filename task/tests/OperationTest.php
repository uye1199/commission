<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paysera\Models\User;
use Paysera\Config\Config;
use Paysera\Models\Currency;
use Paysera\Models\Operation;


class OperationTest extends TestCase
{
    public $currencies;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $config = new Config();
        $this->currencies = $config->getCurrencies();
    }

    public function testGetUser()
    {
        $user = new User(1, 'natural');
        $currency = new Currency('EUR', 1.0, 2);
        $date = '2015-01-01';
        $type = 'cash_out';
        $amount = 1000.00;

        $operation = new Operation($date, $type, $amount, $currency, $user);

        $this->assertEquals($user, $operation->getUser());
    }

    public function testSetUser()
    {
        $user = new User(1, 'natural');
        $user2 = new User(2, 'legal');
        $currency = new Currency('EUR', 1.0, 2);
        $date = '2015-01-01';
        $type = 'cash_out';
        $amount = 1000.00;

        $operation = new Operation($date, $type, $amount, $currency, $user);
        $operation->setUser($user2);

        $this->assertEquals($user2, $operation->getUser());
    }

    public function testSetType()
    {
        $user = new User(1, 'natural');
        $currency = new Currency('EUR', 1.0, 2);
        $date = '2015-01-01';
        $type = 'cash_out';
        $amount = 1000.00;

        $operation = new Operation($date, $type, $amount, $currency, $user);
        $operation->setType('cash_in');

        $this->assertEquals('cash_in', $operation->getType());
    }

    public function testSetIncorrectType()
    {
        $user = new User(1, 'natural');
        $currency = new Currency('EUR', 1.0, 2);
        $date = '2015-01-01';
        $type = 'cash_out';
        $amount = 1000.00;

        $operation = new Operation($date, $type, $amount, $currency, $user);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('illegal type incorrect');

        $operation->setType('incorrect');
    }

    public function testSetCurrency()
    {
        $user = new User(1, 'natural');
        $currency = new Currency('EUR', 1.0, 2);
        $currencyNew = new Currency('USD', 1.1497, 2);
        $date = '2015-01-01';
        $type = 'cash_out';
        $amount = 1000.00;

        $operation = new Operation($date, $type, $amount, $currency, $user);
        $operation->setCurrency($currencyNew);

        $this->assertEquals($currencyNew, $operation->getCurrency());
    }
}
