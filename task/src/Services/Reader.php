<?php

namespace Paysera\Services;

use Paysera\Models\Operation;
use Paysera\Models\User;


class Reader
{
    private $fileSource;
    private $header = false;

    /**
     * Reader constructor.
     * @param $fileSource
     * @param bool $header
     */
    public function __construct($fileSource, $header = false)
    {
        $this->fileSource = $fileSource;
        $this->header = $header;
    }

    /**
     * @return mixed
     */
    public function getFileSource()
    {
        return $this->fileSource;
    }

    /**
     * @param mixed $fileSource
     */
    public function setFileSource($fileSource)
    {
        $this->fileSource = $fileSource;
    }

    /**
     * read csv file and return operations array
     *
     * @return array
     */
    public function readFile()
    {
        $handle = fopen($this->fileSource, "r");
        $result = [];

        while ($csvLine = fgetcsv($handle, 1000, ",")) {

            if ($this->header) {
                $this->header = false;
            } else {

                $operationDate = $csvLine[0];
                $userIdentificator = $csvLine[1];
                $userType = $csvLine[2];
                $operationType = $csvLine[3];
                $operationAmount = $csvLine[4];
                $operationCurrency = $csvLine[5];

                $user = new User($userIdentificator, $userType);

                try {
                    $operation = new Operation(
                        $operationDate, $operationType, $operationAmount, $operationCurrency, $user
                    );
                    $result[] = $operation;

                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }

        return $result;
    }
}
