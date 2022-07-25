<?php

namespace App\Tests\Security;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Security\Voter\UserVoter;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoterTest extends WebTestCase
{
    public const EDIT = 'edit';
    public const DELETE = 'delete';

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
    
    public function testVoteOnAttributeFalse()
    {
        $tokenInterface = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $security = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $voter = new UserVoter($security);
        $this->assertEquals(0, $voter->vote($tokenInterface, (new User()), ["WRONG_ATTRIBUTE"]));
    }

    public function testUserInterfaceFalse()
    {
        $tokenInterface = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $security = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $voter = new UserVoter($security);
        $user = new User();
        $this->assertEquals(true, $voter->vote($tokenInterface, $user, ['edit', 'delete']));
    }

    public function testVote()
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['roles' => ['ROLE_ADMIN']]);
        ;

        $tokenInterface = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        // $security = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $voter = new UserVoter();
        
        $this->assertEquals(0, $voter->vote($tokenInterface, $user, ['edit', 'delete']));
        // $this->assertSame('Il ne se.', $task->getTitle());
    }


}