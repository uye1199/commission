<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paysera\Services\Reader;
use Paysera\Models\Operation;
use Paysera\Models\User;
use Paysera\Services\Config;


class CsvTest extends TestCase
{
    public function testReader()
    {
        $config = new Config();
        $currencies = $config->getCurrencies();

        $reader = new Reader('/var/www/html/tests/input.csv', $currencies);
        $operations = $reader->readFile();

        $operation = new Operation(
            '2014-12-31',
            'cash_out',
            1200.00,
            $currencies['EUR'],
            new User(4, 'natural')
        );

        $this->assertEquals($operation, $operations[0]);
    }
}
