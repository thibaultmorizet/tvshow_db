<?php

namespace App\Controller;

use App\Service\WelcomeMessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DefaultController extends AbstractController
{


    private $welcomeMessageGenerator;
    private $client;

    public function __construct(WelcomeMessageGenerator $welcomeMessageGenerator, HttpClientInterface $client)
    {
        $this->welcomeMessageGenerator = $welcomeMessageGenerator;
        $this->client = $client;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        // recupérer depuis le WelcomeMessageGenerator un message de bienvenue aléatoire

        // on peut recupérer un service directement depuis le container
        // $generator = $this->container->get(WelcomeMessageGenerator::class);

        $welcomeMessage = $this->welcomeMessageGenerator->getRandomMessage();


        // recupérer une joe depuis http://api.icndb.com/jokes/random
        $response = $this->client->request(
            'GET',
            "http://api.icndb.com/jokes/random"
        );
        $content = $response->toArray();

        $joke = $content['value']['joke'];

        return $this->render(
            'default/homepage.html.twig',
            [
                "welcomeMessage" => $welcomeMessage,
                "joke" => $joke
            ]
        );
    }
}
