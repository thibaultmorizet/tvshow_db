<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    public function testPublicUrls()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        
        $client->request('GET', '/tv-show/list');
        $this->assertResponseIsSuccessful();
        
        $client->request('GET', '/categories');
        $this->assertResponseIsSuccessful();
        
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/user/create-account');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminUrls()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/tv-show/add');
        // on verifie que l'on est bien redirigé vers le login
        $this->assertEquals(302, $client->getResponse()->getStatusCode());





        // je me connecte en tant qu'un utilisateur admin
        // 1 - je recupère le repository des User
        $repository = static::$container->get(UserRepository::class);
        // 2 - je recupèe une entité User
        $admin = $repository->findOneBy(['email' => 'admin@mail.com']);
        // 3 - j'utilise la methode loginUser du client pour "connecter" cet utilisateur sur le site
        // derriere cette methode Symfony utilise le service de Security pour authentifier l'utilisateur fourni
        $client->loginUser($admin);

        // je verifie que j'ai bien accès a la page sécurisé
        $client->request('GET', '/admin/tv-show/add');
        $this->assertResponseIsSuccessful();


        
        // on test cette fois on est bien connecté mais pas avec le ROLE_ADMIN
        
        $user = $repository->findOneBy(['email' => 'user@mail.com']);
        $client->loginUser($user);
        $client->request('GET', '/admin/tv-show/add');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());


    }
}
