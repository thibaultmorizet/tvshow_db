<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// les tests fonctionnels extends WebTestCase pour tirer profit du framework symfony dans nos tests
// on va surtout utiliser le $client (une sorte de navigateur piloté par du code)
// test fonctionnel => WebTestCase
class DefaultControllerTest extends WebTestCase
{
    public function testHomePage() 
    {
        // je recupère le client (un faux navigateur web que je vais controllé avec du code PHP)
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        // je vérifie que le code de status correspond bien a 200
        /*
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        */
        $this->assertResponseIsSuccessful();

        // je souhaite vérifer a l'interieur de ma page si j'ai bien un titre
        $this->assertCount(1, $crawler->filter('h1.display-4'), "Le titre de la page est manquant");

    }

    public function testMenu()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $this->assertCount(4, $crawler->filter("#navbarNav a"));
        $this->assertCount(1, $crawler->filter("#navbarNav a:contains('Séries')"));
        $this->assertCount(1, $crawler->filter("#navbarNav a:contains('Catégories')"));
        $this->assertCount(1, $crawler->filter("#navbarNav a:contains('Acteurs')"));
        $this->assertCount(1, $crawler->filter("#navbarNav a:contains('Connexion')"));
    }

    public function testShowListLink()
    {
        
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        //je recupère le lien grace au crawler
        $link = $crawler->filter("#navbarNav a:contains('Séries')")->first()->link();
        // je demande au client de clicker sur ce lien
        // il me fournira un crawler de la nouvelle page suite au clic sur le lien
        $crawler = $client->click($link);
        // je verifie que lorsque j'ai cliqué sur le lien je n'ai pas reçu une erreur
        $this->assertResponseIsSuccessful();
        // je verifie que je suis sur la bonne page en verifiant le titre
        $this->assertSelectorTextContains('html h2', 'Séries');

        // je vérifie que j'ai bien 2 liens vers 2 séries (celles qui sont dans le BDD de test chargée avec les Fixtures)
        $this->assertCount(2, $crawler->filter('.list-group a'), "La liste des séries ne contient pas le bon nombre de séries");

    }

}