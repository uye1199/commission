<?php
namespace Paysera\Models;


class User
{
    const TYPE_NATURAL = 'natural';
    const TYPE_LEGAL = 'legal';
    private $allowedUserTypes;

    private $id;
    private $userType;

    public $userOperations = [];

    public function __construct($id, $userType)
    {
        $this->allowedUserTypes = [self::TYPE_LEGAL, self::TYPE_NATURAL];
        $this->setId($id);
        $this->setUserType($userType);
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
     * @param $userType
     * @throws \Exception
     */
    public function setUserType($userType)
    {
        if (!in_array($userType, $this->allowedUserTypes)){
            throw new \InvalidArgumentException("illegal user type $userType");
        }

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
