<?php

namespace App\Entity\Tests;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testIsTrue()
    {
        $task = new Task();
        $user = new User();
        $datetime = new DateTime();

        $task->setTitle('title');
        $task->setCreatedAt($datetime);
        $task->setContent('content');
        $task->setIsDone(true);
        $task->setAuthor($user);
        

        $this->assertTrue($task->getTitle() === 'title');
        $this->assertTrue($task->getContent() === 'content');
        $this->assertTrue($task->getCreatedAt() === $datetime);
        $this->assertTrue($task->isIsDone() === true);
        $this->assertTrue($task->getAuthor() === $user);

        // $this->assertTrue($task->toggle(true) === true);
    }

    public function testIsFalse()
    {
        $task = new Task();
        $user = new User();
        $datetime = new DateTime();

        $task->setTitle('title');
        $task->setContent('content');
        $task->setCreatedAt($datetime);
        $task->setIsDone(true);
        $task->setAuthor($user);
    

        $this->assertFalse($task->getTitle() === 'titleNo');
        $this->assertFalse($task->getCreatedAt() === new DateTime());
        $this->assertFalse($task->getContent() === 'contentNo');
        $this->assertFalse($task->isIsDone() === false);
        $this->assertFalse($task->getAuthor() === new User());
     
    }

    public function testIsEmpty()
    {
        $task = new Task();

        $this->assertEmpty($task->getTitle());
        $this->assertEmpty($task->getContent());
        $this->assertEmpty($task->isIsDone());
        $this->assertEmpty($task->getId());
        $this->assertEmpty($task->isDone());
        $this->assertEmpty($task->toggle(true));
        $this->assertEmpty($task->getAuthor());
    }

    // public function testAddGetRemoveTask()
    // {
    //     $task = new task();
    //     $task = new Task();

    //     $this->assertEmpty($task->getTasks());

    //     $task->addTask($task);
    //     $this->assertContains($task, $task->getTasks());
        
    //     $task->removeTask($task);
    //     $this->assertEmpty($task->getTasks());
        
    // }
}
