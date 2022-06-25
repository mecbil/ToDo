<?php

namespace App\Entity\Tests;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserAssertTest extends KernelTestCase
{
   public function testValidEntity () {
        $user = new User();

        $user->setUsername('user');
        $user->setPassword('Azerty1+');
        $user->setEmail('moi@moi.fr');
        $user->setRoles(['ROLE_USER']);

        self::bootKernel();
        $error = self::getContainer()->get('validator')->validate($user);

        $this->assertcount(0, $error);

   }

   public function testInvalidEntity () {
    $user = new User();

    $user->setUsername('');
    $user->setPassword('');
    $user->setEmail('');
    $user->setRoles([]);

    self::bootKernel();
    $error = self::getContainer()->get('validator')->validate($user);

    $this->assertcount(4, $error);

}
}
