<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\TvShow;
use App\Form\SeasonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    /**
     * @Route("/season/add/{id}", name="season_add", requirements={"id"="\d+"})
     */
    public function add(TvShow $tvShow, Request $request)
    {
        $season = new Season();
        // j'initialise ma saison pour qu'elle soit liée à la série dont l'id est dans la route
        $season->setTvShow($tvShow);

        $seasonForm = $this->createForm(SeasonType::class, $season);
        $seasonForm->handleRequest($request);
        if($seasonForm->isSubmitted() && $seasonForm->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($season);
            $manager->flush();

            $this->addFlash("success", "La saison a bien été ajoutée");
            return $this->redirectToRoute("tv_show_view", ["id" => $tvShow->getId()]);
        }

        return $this->render('season/add.html.twig', [
            "seasonForm" => $seasonForm->createView()
        ]);
    }

    /**
     * @Route("/season/{id}/update", name="season_update", requirements={"id"="\d+"})
     */
    public function update(Season $season, Request $request)
    {

        $seasonForm = $this->createForm(SeasonType::class, $season);
        $seasonForm->handleRequest($request);
        if($seasonForm->isSubmitted() && $seasonForm->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash("success", "La saison a bien été ajoutée");
            return $this->redirectToRoute("tv_show_view", ["id" => $season->getTvShow()->getId()]);
        }

        return $this->render('season/update.html.twig', [
            "seasonForm" => $seasonForm->createView()
        ]);
    }
}
