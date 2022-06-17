<?php

namespace App\Entity\Tests;

use App\Entity\Task;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testIsTrue()
    {
        $task = new Task();
        $datetime = new DateTime();

        $task->setTitle('title');
        $task->setCreatedAt($datetime);
        $task->setContent('content');
        $task->setIsDone(true);

        $this->assertTrue($task->getTitle() === 'title');
        $this->assertTrue($task->getContent() === 'content');
        $this->assertTrue($task->getCreatedAt() === $datetime);
        $this->assertTrue($task->isIsDone() === true);
    }

    public function testIsFalse()
    {
        $task = new Task();
        $datetime = new DateTime();

        $task->setTitle('title');
        $task->setContent('content');
        $task->setCreatedAt($datetime);
        $task->setIsDone(true);
    

        $this->assertFalse($task->getTitle() === 'titleNo');
        $this->assertFalse($task->getCreatedAt() === new DateTime());
        $this->assertFalse($task->getContent() === 'contentNo');
        $this->assertFalse($task->isIsDone() === false);
     
    }

    public function testIsEmpty()
    {
        $task = new Task();

        $this->assertEmpty($task->getTitle());
        $this->assertEmpty($task->getContent());
        $this->assertEmpty($task->isIsDone());
        $this->assertEmpty($task->getId());
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
