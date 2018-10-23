<?php

namespace Paysera\Services;

use Paysera\Models\Operation;
use Paysera\Models\User;


class Reader
{
    private $file_source;
    private $header = false;

    /**
     * Reader constructor.
     * @param $file_source
     * @param bool $header
     */
    public function __construct($file_source, $header = false)
    {
        $this->file_source = $file_source;
        $this->header = $header;
    }

    /**
     * @return mixed
     */
    public function getFileSource()
    {
        return $this->file_source;
    }

    /**
     * @param mixed $file_source
     */
    public function setFileSource($file_source)
    {
        $this->file_source = $file_source;
    }

    /**
     * read csv file and return operations array
     *
     * @return array
     */
    public function readFile()
    {
        $handle = fopen($this->file_source, "r");
        $result = [];

        while ($csvLine = fgetcsv($handle, 1000, ",")) {

            if ($this->header) {
                $this->header = false;
            } else {

                $operation_date = $csvLine[0];
                $user_identificator = $csvLine[1];
                $user_type = $csvLine[2];
                $operation_type = $csvLine[3];
                $operation_amount = $csvLine[4];
                $operation_currency = $csvLine[5];

                $user = new User($user_identificator, $user_type);

                try {
                    $operation = new Operation(
                        $operation_date, $operation_type, $operation_amount, $operation_currency, $user
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
