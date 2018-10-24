<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paysera\Services\Reader;
use Paysera\Models\Operation;
use Paysera\Models\User;
use Paysera\Config\Config;


class CsvTest extends TestCase
{
    public function testReader()
    {
        $config = new Config();
        $currencies = $config->getCurrencies();

        $reader = new Reader('csv/test.csv', $currencies);
        $operations = $reader->readFile();

        $operation1 = new Operation(
            '2014-12-31',
            'cash_out',
            1200.00,
            $currencies['EUR'],
            new User(4, 'natural')
        );

        $operation2 = new Operation(
            '2015-01-01',
            'cash_out',
            1000.00,
            $currencies['EUR'],
            new User(4, 'natural')
        );

        $this->assertEquals($operation1, $operations[0]);
        $this->assertEquals($operation2, $operations[1]);
    }
}
