<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Entity\Task;
use App\Security\Voter\TaskVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TaskVoterTest extends TestCase
{
    private $task;
    private $voter;
    private $token;
    private $user;

    public function setUp():void
    {
        $this->task = new Task();
        $this->voter = new TaskVoter();
        $this->user = $this->createMock(User::class);
        $this->token = new UsernamePasswordToken($this->user, 'credentials', 'memory');
    }

    public function testTaskVoterEditAuthor()
    {
        $this->user->method('getId')->willReturn(1);
        $this->task->setUser($this->user);
        $this->assertSame(1, $this->voter->vote($this->token, $this->task, ['edit']));
    }

    // public function testTaskVoterEditNotAuthor()
    // {
    //     $this->user->method('getId')->willReturn(1);
    //     $taskUser = $this->createMock(User::class);
    //     $taskUser->method('getId')->willReturn(2);
    //     $this->task->setUser($taskUser);
    //     $this->assertSame(-1, $this->voter->vote($this->token, $this->task, ['EDIT']));
    // }

    // public function testTaskVoterEditNoUser()
    // {
    //     $token = new AnonymousToken('secret', 'anonymous');
    //     $this->assertSame(-1, $this->voter->vote($token, $this->task, ['EDIT']));
    // }

    // public function testTaskVoterEditAdmin()
    // {
    //     $this->user->method('isAdmin')->willReturn(1);
    //     $taskUser = $this->createMock(User::class);
    //     $taskUser->method('isAnon')->willReturn(1);
    //     $this->task->setUser($taskUser);
    //     $this->assertSame(1, $this->voter->vote($this->token, $this->task, ['EDIT']));
    // }

    // public function testTaskVoterDelete()
    // {
    //     $this->user->method('getId')->willReturn(1);
    //     $this->task->setUser($this->user);
    //     $this->assertSame(1, $this->voter->vote($this->token, $this->task, ['DELETE']));
    // }

    // public function testTaskVoterDeleteAdmin()
    // {
    //     $this->user->method('isAdmin')->willReturn(1);
    //     $taskUser = $this->createMock(User::class);
    //     $taskUser->method('isAnon')->willReturn(1);
    //     $this->task->setUser($taskUser);
    //     $this->assertSame(1, $this->voter->vote($this->token, $this->task, ['DELETE']));
    // }

    // public function testTaskVoterDeleteNotAuthor()
    // {
    //     $this->user->method('getId')->willReturn(1);
    //     $taskUser = $this->createMock(User::class);
    //     $taskUser->method('getId')->willReturn(2);
    //     $this->task->setUser($taskUser);
    //     $this->assertSame(-1, $this->voter->vote($this->token, $this->task, ['DELETE']));
    // }
}