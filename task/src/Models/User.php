<?php
namespace Paysera\Models;


class User
{
    private $id;
    private $user_type;

    public static $userOperations = [];
    public static $TYPE_NATURAL = 'natural';
    public static $TYPE_LEGAL = 'legal';

    public function __construct($id, $user_type)
    {
        $this->id = $id;
        $this->user_type = $user_type;
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
        return $this->user_type;
    }

    /**
     * @param mixed $user_type
     */
    public function setUserType($user_type)
    {
        $this->user_type = $user_type;
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
