<?php

namespace App\Tests\Security;

use App\Entity\Task;
use App\Security\Voter\TaskVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VoterTest extends WebTestCase
{
    public function testVoteOnAttributeFalse()
    {
        $tokenInterface = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $security = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $voter = new TaskVoter($security);
        $this->assertEquals(0, $voter->vote($tokenInterface, (new Task()), ["WRONG_ATTRIBUTE"]));
    }
}