<?php

namespace App\EventListener;

use App\Entity\TvShow;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
    
// on a configuré ce service avec le tag "doctrine.orm.entity_listener"
// voir la config dans services.yml
class TvShowUpdateLogger 
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    // Cette methode va être appelée par le systeme de gestion des evenements de Doctrine
    public function onTvShowUpdate(TvShow $tvShow, LifecycleEventArgs $event)
    {
        $this->logger->info(
            "Modification de " . $tvShow->getTitle()
        );
    }
}