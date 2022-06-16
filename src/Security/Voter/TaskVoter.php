<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    private $security;

    public function __construct(security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $task): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $task instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute(string $attribute, $task, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        if ($this->security->isGranted('ROLE_ADMIN')) return true;

        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return $this->canEdit($task, $user);
                
                break;
            case self::DELETE:
                // logic to determine if the user can VIEW
                // return true or false
                return $this->canDelete($task, $user);

                break;
        }

        return false;
    }

    private function canEdit(Task $task, User $user)
    {
        // L’auteur de la tâche peut l’éditer
        
        return $user === $task->getAuthor();
    }

    private function canDelete(Task $task, User $user)
    {
        // L’auteur de la tâche peut la supprimer
        return $user === $task->getAuthor();
    }
}
