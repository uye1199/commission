<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paysera\Services\Commission;
use Paysera\Models\User;
use Paysera\Models\Operation;
use Paysera\Config\Config;

class CommissionTest extends TestCase
{
    public $currencies ;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $config = new Config();
        $this->currencies = $config->getCurrencies();
    }

    public function testCalculateCashIn()
    {
        $user = new User(1, 'natural');
        $operation = new Operation('2016-01-05', 'cash_in', 200.00, $this->currencies['EUR'], $user);
        $commission = new Commission([$operation]);
        $result = $commission->process();

        $this->assertEquals([0.06], $result);
    }

    public function testCalculateLegalCashOut()
    {
        $user = new User(4, 'legal');
        $operation = new Operation('2016-01-06', 'cash_out', 300.00, $this->currencies['EUR'], $user);
        $commission = new Commission([$operation]);
        $result = $commission->process();

        $this->assertEquals([0.90], $result);
    }

    public function testCalculateNaturalCashOut()
    {

        $user = new User(4, 'natural');
        $operation = new Operation('2016-01-05', 'cash_out', 1200.00, $this->currencies['EUR'], $user);
        $commission = new Commission([$operation]);
        $result = $commission->process();

        $this->assertEquals([0.60], $result);
    }

    public function testCalculateNaturalCashOutJpy()
    {

        $user = new User(1, 'natural');
        $operation = new Operation('2016-01-06', 'cash_out', 30000.00, $this->currencies['JPY'], $user);
        $commission = new Commission([$operation]);
        $result = $commission->process();

        $this->assertEquals([0], $result);
    }

    public function testCalculateNaturalCashOutMultiple()
    {

        $user = new User(1, 'natural');
        $operation_1 = new Operation('2014-12-31', 'cash_out', 1200.00, $this->currencies['EUR'], $user);
        $operation_2 = new Operation('2015-01-01', 'cash_out', 1000.00, $this->currencies['EUR'], $user);
        $commission = new Commission([$operation_1, $operation_2]);
        $result = $commission->process();

        $this->assertEquals([0.60, 3.00], $result);
    }
}