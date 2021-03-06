<?php
namespace Paysera\Models;

class Operation
{
    private $date;
    private $type;
    private $amount;
    private $currency;
    private $user;
    private $allowedTypes = ['cash_in', 'cash_out'];
    private $week;


    /**
     * Operation constructor.
     * @param $date
     * @param $type
     * @param $amount
     * @param $currency
     * @param User $user
     * @throws \Exception
     */
    public function __construct($date, $type, $amount, Currency $currency, User $user)
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new \Exception('operation type inconsistent', 100);
        }

        $this->setDate($date);
        $this->setType($type);
        $this->setAmount($amount);
        $this->setCurrency($currency);
        $this->setUser($user);
        $this->setWeek($this->getDate());
    }

    /**
     * @return int
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * Set week number. Weeks start from unix timestamp starting date 01-01-1970
     * @param \DateTime $date
     */
    public function setWeek(\DateTime $date)
    {
        $this->week = floor(($date->getTimestamp() - 86400 * 4) / (86400 * 7)) + 1;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = new \DateTime($date);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        if (!in_array($type, $this->allowedTypes)){
            throw new \InvalidArgumentException("illegal type $type");
        }

        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = floatval($amount);
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
}
