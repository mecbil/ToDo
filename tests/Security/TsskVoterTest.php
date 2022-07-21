<?php

namespace App\Tests\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TaskVoterTest extends WebTestCase
{
    private function createUser(): User
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        return $user;
    }

    private function createTask($user = null): Task
    {
        $task = new Task();
        $task->setAuthor($user);

        return $task;
    }

}