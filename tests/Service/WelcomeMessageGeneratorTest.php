<?php

namespace App\Test\Service;

use App\Service\WelcomeMessageGenerator;
use PHPUnit\Framework\TestCase;

class WelcomeMessageGeneratorTest extends TestCase
{
    public function testGetRandomMessage() 
    {
        $generator = new WelcomeMessageGenerator();
        $message = $generator->getRandomMessage();

        // je verifie que $message contient bien l'un des 3 message possibles
        $this->assertContains(
            $message, 
            [
                "Coucou les p'tit loups !",
                "Salut les amis !",
                "Kapoué !"
            ]
        );
    }
}