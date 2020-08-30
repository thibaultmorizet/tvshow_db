<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TvShowControllerTest extends WebTestCase
{
    public function testTvShowSearch()
    {
        $client = static::createClient();
        // je navigue directement vers l'URL /tv-show/list
        $crawler = $client->request('GET', '/tv-show/list');
        // je verifie que le code de status est bien 200
        $this->assertResponseIsSuccessful();

        // je recupère un objet qui represente le formulaire
        $form = $crawler->selectButton("Rechercher")->form();
        // je rempli le formulaire avec des données
        // un champs => une donnée
        $form['search'] = "star";

        // je soumait le formulaire 
        // donc on va changer de page
        // je recupère donc le $crawler de la nouvelle page
        $crawler = $client->submit($form);

        // je verifie que sur la nouvelle page j'ai bien le resultat de recherche attendu
        // je verifie que j'ai bien un suel résultat (correspond a mes fixtures)
        $this->assertCount(1, $crawler->filter('.list-group a'));
        $this->assertCount(1, $crawler->filter(".list-group a:contains('Stargate SG-1')"));

    }
}
