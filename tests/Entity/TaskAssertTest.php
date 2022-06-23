<?php

namespace App\Entity\Tests;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskAssertTest extends KernelTestCase
{
   public function testValidEntity () {
        $task = new Task();
        $user = new User();
        $datetime = new DateTime();

        $task->setTitle('title');
        $task->setContent('content');
        $task->setCreatedAt($datetime);
        $task->setIsDone(true);
        $task->setAuthor($user);

        self::bootKernel();
        $error = self::getContainer()->get('validator')->validate($task);

        $this->assertcount(0, $error);

   }

   public function testInvalidEntity () {
      $task = new Task();

      $task->setTitle('');
      $task->setContent('');

      self::bootKernel();
      $error = self::getContainer()->get('validator')->validate($task);

      $this->assertcount(2, $error);

 }

}
