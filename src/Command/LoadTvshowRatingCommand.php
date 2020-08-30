<?php

namespace App\Command;

use App\Entity\TvShow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LoadTvshowRatingCommand extends Command
{
    protected static $defaultName = 'app:load-tvshow-rating';

    private $manager;
    private $client;

    public function __construct(EntityManagerInterface $manager, HttpClientInterface $client)
    {   
        $this->manager = $manager;
        $this->client = $client;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Récupération des notes des séries depuis  OMDb API');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $apiKey = "290595bb";
        $apiUrl = "http://www.omdbapi.com/";

        $io = new SymfonyStyle($input, $output);
 
        $repository = $this->manager->getRepository(TvShow::class);
        // recupérer la liste de nos series
        $tvShows = $repository->findAll();

        // contacter l'api pour chaque serie
        foreach($tvShows as $tvShow)
        {
            // je recupère le tittre de ma serie (c'est ce que je vais chercher sur l'API)
            $title = $tvShow->getTitle();

            // j'appelle l'api par son URL en methode GET
            // j'ajoute dans l'URL des parametre grace à l'option "query"
            // les paramètres obligatoire sont apiKey pour etre reconnyu par l'API
            // ET le titre de la serie recherchée
            // j'ajout en plus le parametre optionnel "type" pour filtrer la recherche uniquuement sur les series
            // car OMDb API peut rechercher des films et des series , donc ajouter un filtre me permer de cibler plus spécifiquement ma recherche
            // on recherche donc avec titre + filtre:series
            $response = $this->client->request(
                'GET',
                $apiUrl,
                [
                    "query" => [
                        "apikey" => $apiKey,
                        "type" => "series",
                        "t" => $title
                    ]
                ]
            );

            // si l'api me repond autre chose qu'on code 200 
            $statusCode = $response->getStatusCode();
            if($statusCode != 200) {
                // ca veut dire qu'il y a un pépin j'arrete la boucle ici
                // pas besoin d'aller plus loin
                $io->error($title . " : Code d'erreur " . $statusCode);
                continue;
            }

            // je recupère le contenu et le transforme en tableau facile a parcourir
            // le $client symfony a donc fait une transformation JSON -> array
            $content = $response->toArray();

            // si l'API m'a repondu cool
            // mais je verifie si l'API m'a repondu une erreur
            if(isset($content['Error'])) {
                // si l'api me repond par exemple qu'elle n'a pas trouvé ma serie
                // alors inutile d'aller plus loin on passe a la serie suivante
                $io->error($title . " : " . $content['Error']);
                continue;
            }

            // si j'ai aucune erreur alors NORMALEMENT l'api ma transmis un JSON
            // qui contient la donnée imdbRating
            $rating = $content['imdbRating'];

            $tvShow->setRating($rating);
        }

        // enregistrer les modifications faites sur les series
        $this->manager->flush();

        // mettre un message de log pour dire que tout est OK
        $io->success("Tout s'est bien passé");

        // on renvoi 0 pour dire que tout va bien
        return Command::SUCCESS;
    }
}
