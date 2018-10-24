<?php
namespace Paysera\Models;


class User
{
    const TYPE_NATURAL = 'natural';
    const TYPE_LEGAL = 'legal';

    private $id;
    private $userType;

    public $userOperations = [];

    public function __construct($id, $userType)
    {
        $this->id = $id;
        $this->userType = $userType;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /**
     * @param $year
     * @param $month
     * @param $week
     * @param $amount
     */
    public function setWeeklyUserOperationAmount($week, $amount)
    {
        $this->userOperations[$week][] = $amount;
    }

    /**
     * @return mixed
     */
    public function getUserOperations()
    {
        return $this->userOperations;
    }

    /**
     * @param $year
     * @param $month
     * @param $week
     * @return array
     */
    public function getAmoutOfUserOperationsForWeek($week)
    {
        $operations = $this->getUserOperations();
        $weeklyOperations = $operations[$week];
        $count = 0;
        $amount = 0;

        if (is_array($weeklyOperations) && count($weeklyOperations) > 0) {
            foreach ($weeklyOperations as $key => $value) {
                $amount += $value;
                $count++;
            }
        }

        return ['amount' => $amount, 'count' => $count];
    }
}
