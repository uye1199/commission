<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Paysera\Services\Reader;
use \Paysera\Services\Commission;
use Paysera\Models\Currency;
use Paysera\Config\Config;

if ($argc != 2) {
    die('File Parameter is empty!');
}


if (isset($argv[1])) {
    $config = new Config();
    $currencies = $config->getCurrencies();

    $reader = new Reader($argv[1], $currencies);
    $operations = $reader->readFile();

    $commissions = new Commission($operations);
    $result = $commissions->process();

    foreach ($result as $key => $value) {
        echo $value . "\n";
    }
}
