<?php

namespace App\Controller;

use App\Entity\TvShow;
use App\Form\TvShowType;
use App\Service\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_TVSHOW_ADMIN")
 * @Route("/admin/tv-show")
 */
class TvShowAdminController extends AbstractController
{

    private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @Route("/add", name="tv_show_add")
     */
    public function add(Request $request)
    {
        // on prefere les annotations
        // $this->denyAccessUnlessGranted('ROLE_TVSHOW_ADMIN');

        // je crée un objet
        $tvShow = new TvShow();

        // je demande a créer un formulaire grace à ma classe de formulaire
        // et je fourni a mon nouveau formulaire l'objet qu'il doit manipuler
        $form = $this->createForm(TvShowType::class, $tvShow);
        // je demande au formulaire de recupérer les données dans la request
        $form->handleRequest($request);
        // automatiquement le formulaire a mis a jour mon objet $tvShow

        // Si des données ont été soumises dans le formulaire
        if($form->isSubmitted() && $form->isValid()) {

            // je souhaite enregistrer le slug du titre de ma serie
            $slug = $this->slugger->sluggify($tvShow->getTitle());
            $tvShow->setSlug($slug);

            
            // si je souhaite ajouter cette entité en base de donnée j'ai besoin du manager
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($tvShow);
            $manager->flush();
            $this->addFlash("success", "La série a bien été ajoutée");
            return $this->redirectToRoute('tv_show_list');
        }

        // on envoi une representation simplifiée du formulaire dans la template
        return $this->render(
            'tv_show/add.html.twig',
            [
                "tvShowForm" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/{id}/update", name="tv_show_update", requirements={"id"="\d+"})
     */
    public function update(TvShow $tvShow, Request $request)
    {
        $form = $this->createForm(TvShowType::class, $tvShow);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager(); 
            $manager->flush();
            
            $this->addFlash("success", "La série a bien été mise à jour");
            // je redirige vers la page qui affiche le detail de la series que l'on vient de modifier
            return $this->redirectToRoute('tv_show_view', ["id" => $tvShow->getId()]);
        }

        return $this->render(
            'tv_show/update.html.twig',
            [
                "tvShowForm" => $form->createView(),
                "tvShow" => $tvShow
            ]
        );
    }

     /**
     * @Route("/{id}/delete", name="tv_show_delete", requirements={"id"="\d+"})
     */
    public function delete(TvShow $tvShow)
    {
        // 1 - on recupère l'entité à supprimer (param converter / repository)
        // Nous on l'a fait avec le param converter

        // 2 - on recupère le manager
        $manager = $this->getDoctrine()->getManager();

        // 3 - on demande au manager de supprimer l'entité
        $manager->remove($tvShow);
        $manager->flush();

        // 4 - on met un message pour dire que ca s'est bien passé
        $this->addFlash("success", "La série a bien été supprimée");

        // 5 - on redirige vers une page qui montre l'effet (la liste des series, on va pouvoir voir que la serie n'y est plus)
        return $this->redirectToRoute('tv_show_list');
    }
}
