<?php

namespace App\Entity\Tests;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIsTrue()
    {
        $user = new User();

        $user->setUsername('username');
        $user->setPassword('password');
        $user->setEmail('moi@moi.fr');
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertTrue($user->getUsername() === 'username');
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getEmail() === 'moi@moi.fr');
        $this->assertTrue($user->getRoles() === ['ROLE_ADMIN']);
    }

    public function testIsFalse()
    {
        $user = new User();

        $user->setUsername('username');
        $user->setPassword('password');
        $user->setEmail('moi@moi.fr');
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertFalse($user->getUsername() === 'usernameNo');
        $this->assertFalse($user->getPassword() === 'passwordNo');
        $this->assertFalse($user->getEmail() === 'moiNo@moi.fr');
        $this->assertFalse($user->getRoles() === ['ROLE_ADMIN_No']);
    }

    public function testIsEmpty()
    {
        $user = new User();

        $this->assertEmpty($user->getUsername());
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getRoles());
        $this->assertEmpty($user->getId());
        $this->assertEmpty($user->getUserIdentifier());
        $this->assertEmpty($user->getSalt());
        $this->assertEmpty($user->eraseCredentials());
    }

    public function testAddGetRemoveTask()
    {
        $user = new User();
        $task = new Task();

        $this->assertEmpty($user->getTasks());

        $user->addTask($task);
        $this->assertContains($task, $user->getTasks());
        
        $user->removeTask($task);
        $this->assertEmpty($user->getTasks());
    }
}
