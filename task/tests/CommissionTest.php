<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paysera\Services\Commission;
use Paysera\Models\User;
use Paysera\Models\Operation;

class CommissionTest extends TestCase
{
    public function testCalculateCashIn(){

        $user = new User(1, 'natural');
        try{
            $operation = new Operation('2016-01-05','cash_in',200.00,'EUR',$user);
        } catch (\Exception $e) {

        }

        $commission = new Commission([$operation]);
        $result = $commission->process();

        $this->assertEquals([0.06], $result);
    }

    public function testCalculateLegalCashOut(){

        $user = new User(4, 'legal');
        try{
            $operation = new Operation('2016-01-06','cash_out',300.00,'EUR',$user);
        } catch (\Exception $e) {

        }

        $commission = new Commission([$operation]);
        $result = $commission->process();

        $this->assertEquals([0.90], $result);
    }

    public function testCalculateNaturalCashOut(){

        $user = new User(4, 'natural');
        try{
            $operation = new Operation('2016-01-05','cash_out',1200.00,'EUR',$user);
        } catch (\Exception $e) {

        }

        $commission = new Commission([$operation]);
        $result = $commission->process();

        $this->assertEquals([0.60], $result);
    }

    public function testCalculateNaturalCashOutJpy(){

        $user = new User(1, 'natural');
        try{
            $operation = new Operation('2016-01-06','cash_out',30000.00,'JPY', $user);
        } catch (\Exception $e) {

        }

        $commission = new Commission([$operation]);
        $result = $commission->process();

        $this->assertEquals([0], $result);
    }

    public function testCalculateNaturalCashOutMultiple(){

        $user = new User(1, 'natural');
        $commission = [];

        try{
            $operation_1 = new Operation('2014-12-31','cash_out',1200.00,'EUR', $user);
            $operation_2 = new Operation('2015-01-01','cash_out',1000.00,'EUR', $user);

            $commission = new Commission([$operation_1, $operation_2]);

        } catch (\Exception $e) {

        }

        $result = $commission->process();

        $this->assertEquals([0.60, 3.00], $result);
    }
}