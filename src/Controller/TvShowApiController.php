<?php

namespace App\Controller;

use App\Entity\TvShow;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/tv-show")
 */
class TvShowApiController extends AbstractController
{
    /**
     * @Route("/list", name="api_tv_show_list")
     */
    public function list(TvShowRepository $repository)
    {
        $tvShows = $repository->findAll();
        // j'utilise le serializer de symfony pour transformer mes objet en JSON
        return $this->json(
            $tvShows, 
            200,
            [],
            ["groups" => ["tvshow:list"]]
        );
    }

    /**
     * @Route("/{id}", name="api_tv_show_view", requirements={"id"="\d+"})
     */
    public function view(TvShow $tvShow)
    {
        return $this->json(
            $tvShow,
            200,
            [],
            ["groups" => ["tvshow:read"]]
        );
    }

    /**
     * @Route("/add", name="api_tv_show_add", methods={"POST"})
     */
    public function add(SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        try {
            // transforme le JSON en objet de type TvShow
            $tvShow = $serializer->deserialize(
                $request->getContent(),
                TvShow::class,
                'json'
            );

            //Si le contenu de la requete n'est pas du JSON correct
            // le deserializer va emettre une exception
        } catch (NotEncodableValueException $exception) {
            // si c'est le cas on renvoi a celui qui appel l'API une erreur
            return $this->json(
                [
                "success" => false,
                "error" => $exception->getMessage()
                ], 
                Response::HTTP_BAD_REQUEST
            );
        }

        // Avant de persister l'objet on verifie que son contenu est correct
        // on demande au validator de comparer les propriété de mon objet avec les contrainte de validation (@Assert)
        $errors = $validator->validate($tvShow);
        // Si on trouve des erreur
        if($errors->count() > 0) {

            // on renvoi a celui qui a appelé l'API les erreur trouvées par le validator
            return $this->json(
                [
                "success" => false,
                "errors" => $errors
                ], 
                Response::HTTP_BAD_REQUEST
            );
        }

        // si tout est ok alors on enregistre l'objet en BDD
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($tvShow);
        $manager->flush();

        // on renvoi un petit message de confirmation de tout est OK
        return $this->json(
            [
            "success" => true,
            "id" => $tvShow->getId()
            ], 
            Response::HTTP_CREATED
        );
    }

}
