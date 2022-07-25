<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    private $security;

    // public function __construct(Security $security)
    // {
    //     $this->security = $security;
    // }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\User;
    }

    /**
     * @param $attribute
     * @param $user
     * @param TokenInterface $token
     *
     * @return boolean
     */
    protected function voteOnAttribute(string $attribute, $user, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        if ($this->security->isGranted('ROLE_ADMIN')) return true;
        
        switch ($attribute) {
            case self::EDIT:
                if ($this->security->isGranted('ROLE_ADMIN')) return true;

                break;

            case self::DELETE:
                if ($this->security->isGranted('ROLE_ADMIN')) return true;

                break;
        }
        return false;
    }

    
}
