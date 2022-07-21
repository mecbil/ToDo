<?php

namespace App\Tests\Security;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Security\Voter\TaskVoter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoterTest extends TestCase
{
    public function testVoteOnAttributeFalse()
    {
        $tokenInterface = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $security = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $voter = new TaskVoter($security);
        $this->assertEquals(0, $voter->vote($tokenInterface, (new User()), ["WRONG_ATTRIBUTE"]));
    }
}