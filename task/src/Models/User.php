<?php
namespace Paysera\Models;


class User
{
    private $id;
    private $userType;

    public static $userOperations = [];
    public static $TYPE_NATURAL = 'natural';
    public static $TYPE_LEGAL = 'legal';

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
     * @param User $user
     * @param $year
     * @param $month
     * @param $week
     * @param $amount
     */
    public static function setUserOperations(User $user, $year, $month, $week, $amount)
    {
        if ($month == 12  && $week == 1) {
            $year += 1;
        }

        self::$userOperations[$user->getId()][$year][$week][] = $amount;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public static function getUserOperations(User $user)
    {
        return self::$userOperations[$user->getId()];
    }

    /**
     * @param User $user
     * @param $year
     * @param $month
     * @param $week
     * @return array
     */
    public static function getAmoutOfUserOperationsforWeek(User $user, $year, $month, $week)
    {
        if ($month == 12  && $week == 1) {
            $year += 1;
        }

        $operations = self::getUserOperations($user)[$year][$week];
        $count = 0;
        $amount = 0;

        if (is_array($operations) && count($operations) > 0) {
            foreach ($operations as $key => $value) {
                $amount += $value;
                $count++;
            }
        }


        return ['amount' => $amount, 'count' => $count];
    }
}
