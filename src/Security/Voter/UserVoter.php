<?php

namespace App\Security\Voter;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    /**
     * @param $attribute
     * @param $subject
     *
     * @return boolean
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['GET', 'EDIT'])
            && $subject instanceof \App\Entity\User;
    }

    /**
     * @param $attribute
     * @param $user
     * @param TokenInterface $token
     *
     * @return boolean
     */
    protected function voteOnAttribute($attribute, $user, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        if ($this->security->isGranted('ROLE_ADMIN')) return true;
        
        switch ($attribute) {
            case 'GET':
                if ($user->getRoles(['Role_Admin'])) {
                    return true;
                }
                break;
            case 'EDIT':
                if ($user->getRoles(['Role_Admin'])) {
                    return true;
                }
                break;
        }
        return false;
    }
}
