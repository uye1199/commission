<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paysera\Models\User;

class UserTest extends TestCase
{
    public function testGetUserId()
    {
        $user = new User(1, 'natural');

        $this->assertEquals(1, $user->getId());
    }


    public function testSetUserId()
    {
        $user = new User(1, 'natural');
        $user->setId(2);

        $this->assertEquals(2, $user->getId());
    }

    public function testSetUserType()
    {
        $user = new User(1, 'natural');
        $user->setUserType('legal');

        $this->assertEquals('legal', $user->getUserType());
    }

    public function testSetIncorrectUserType()
    {
        $user = new User(1, 'natural');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('illegal user type incorrect');

        $user->setUserType('incorrect');
    }

    public function testSetWeeklyUserOperationAmount()
    {
        $user = new User(1, 'natural');
        $user->setWeeklyUserOperationAmount(10, 1200);
        $this->assertEquals(['amount' => 1200, 'count' => 1], $user->getAmoutOfUserOperationsForWeek(10));

        $user->setWeeklyUserOperationAmount(10, 1000);
        $this->assertEquals(['amount' => 2200, 'count' => 2], $user->getAmoutOfUserOperationsForWeek(10));
    }
}
