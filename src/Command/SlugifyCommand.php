<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\SluggerInterface;

class SlugifyCommand extends Command
{
    protected static $defaultName = 'app:slugify';

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Slugifier une chaine de caractères')
            ->addArgument('stringToSlug', InputArgument::OPTIONAL, 'La chaine de caractère à slugifier')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // je recupère l'argument nommé stringToSlug (ce que l'on ecrit juste apres le nom de la commande)
        $stringToSlug = $input->getArgument('stringToSlug');

        if(empty($stringToSlug )) {
            // pose une question a l'utilisateur 
            $helper = $this->getHelper('question');
            $question = new Question("Quelle est la chaine de caractère à convertir ?");
            // on recupère la reponse
            $stringToSlug = $helper->ask($input, $output, $question);
        }

        // j'utilise le slugger de Symfo pour convertir ma chaine
        $slug = $this->slugger->slug($stringToSlug);
        // j'affiche la chaine sluggé
        $io->text("Slugified string : " . $slug);

        return Command::SUCCESS;
    }
}
