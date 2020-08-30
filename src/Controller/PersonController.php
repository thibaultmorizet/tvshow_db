<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\TvShow;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class PersonController extends AbstractController
{

    /**
     * @Route("/person", name="person_list")
     */
    public function list()
    {
        /** @var PersonRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Person::class);
        $persons = $repository->findAllOrderedByFirstName();

        return $this->render('person/list.html.twig', ["persons" => $persons]);
    }

    /**
     * @Route("/person/{id}", name="person_view", requirements={"id"="\d+"})
     */
    public function view($id)
    {
        /** @var PersonRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Person::class);
        $person = $repository->findWithCollections($id);

        return $this->render(
            'person/view.html.twig',
             [
                 "person" => $person
             ]
        );
    }

    /**
     * @Route("/person/add", name="person_add")
     * @isGranted("ROLE_ADMIN")
     */
    public function add(Request $request)
    {
        // je crée un objet
        $person = new Person();

        // je demande a créer un formulaire grace à ma classe de formulaire
        // et je fourni a mon nouveau formulaire l'objet qu'il doit manipuler
        $form = $this->createForm(PersonType::class, $person);
        // je demande au formulaire de recupérer les données dans la request
        $form->handleRequest($request);
        // automatiquement le formulaire a mis a jour mon objet $tvShow

        // Si des données ont été soumises dans le formulaire
        if($form->isSubmitted() && $form->isValid()) {
            // si je souhaite ajouter cette entité en base de donnée j'ai besoin du manager
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($person);
            $manager->flush();
            $this->addFlash("success", "L'acteur a bien été ajoutée");
            return $this->redirectToRoute('person_list');
        }

        // on envoi une representation simplifiée du formulaire dans la template
        return $this->render(
            'person/add.html.twig',
            [
                "personForm" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/person/update/{id}", name="person_update", requirements={"id"="\d+"})
     * @isGranted("ROLE_ADMIN")
     */
    public function update(Person $person, Request $request)
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager(); 
            $manager->flush();
            
            $this->addFlash("success", "L'acteur a bien été mise à jour");
            // je redirige vers la page qui affiche le detail de la categorie que l'on vient de modifier
            return $this->redirectToRoute('person_list');
        }

        return $this->render(
            'person/update.html.twig',
            [
                "personForm" => $form->createView(),
                "person" => $person
            ]
        );
    }

    /**
     * @Route("/person/delete/{id}", name="person_delete", requirements={"id"="\d+"})
     * @isGranted("ROLE_ADMIN")
     */
    public function delete(Person $person)
    {        
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($person);
            $manager->flush();
            $this->addFlash("success", "L'acteur a bien été supprimé");

            return $this->redirectToRoute('person_list');

    }
}
