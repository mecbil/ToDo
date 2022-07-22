<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testSearchByNameOk()
    {
        $task = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'Deschamps'])
        ;

        $this->assertSame('Deschamps', $task->getUsername());
    }

    public function testSearchByNameNo()
    {
        $task = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'coucou'])
        ;

        $this->assertNull($task);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}