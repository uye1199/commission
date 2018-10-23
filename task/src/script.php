<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Paysera\Services\Reader;
use \Paysera\Services\Commission;

if ($argc != 2) {
    die('File Parameter is empty!');
}


if (isset($argv[1])) {
    $reader = new Reader($argv[1]);
    $operations = $reader->readFile();

    $commissions = new Commission($operations);
    $r = $commissions->process();

    print_r($r);
}

