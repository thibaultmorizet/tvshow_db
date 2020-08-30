<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class WelcomeMessageGenerator
{

    public function getRandomMessage()
    {
        $anonymousMessages = [
            "Coucou les p'tit loups !",
            "Salut les amis !",
            "Kapoué !"
        ];

        $message = $anonymousMessages[mt_rand(0, count($anonymousMessages) - 1)];

        return $message;

    }
}