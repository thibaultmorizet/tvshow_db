<?php

namespace App\Controller;

use App\Entity\TvShow;
use App\Form\TvShowType;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tv-show")
 */
class TvShowController extends AbstractController
{
    /**
     * @Route("/list", name="tv_show_list")
     */
    public function list(Request $request)
    {
        $search = $request->query->get('search');

        /** @var TvShowRepository $repository */
        $repository = $this->getDoctrine()->getRepository(TvShow::class);
        $tvShows = $repository->findByTitle($search);
        
        return $this->render(
            'tv_show/list.html.twig',
            [
                "tvShows" => $tvShows
            ]
        );
    }

    /**
     * @Route("/{id}", name="tv_show_view", requirements={"id"="\d+"})
     */
    public function view($id)
    {
        /** @var TvShowRepository $repository */
        $repository = $this->getDoctrine()->getRepository(TvShow::class);
        $tvShow = $repository->findWithCollections($id);

        return $this->render(
            'tv_show/view.html.twig',
             [
                 "tvShow" => $tvShow
             ]
        );
    }
    
}
