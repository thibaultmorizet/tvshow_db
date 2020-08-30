<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Person;
use App\Entity\TvShow;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TvShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        
        $builder->add(
            "synopsis",
            TextareaType::class,
            [
                "label" => "Synopsis",
                "required" => false,
                "attr" => [
                    "rows" => 5
                ]
            ]
        );
        
        $builder->add(
            "releaseDate",
            DateType::class,
            [
                "label" => "Date de première diffusion",
                "required" => false,
                "widget" => "single_text"
            ]
        );

        $builder->add(
            "categories",
            EntityType::class,
            [
                "class" => Category::class,
                "choice_label" => "label",
                "multiple" => true,
                "expanded" => true,
                "attr" => [
                    "class" => "compact-select-list"
                ]
            ]
        );

        $builder->add(
            "directedBy",
            EntityType::class,
            [
                "class" => Person::class,
                "choice_label" => function (Person $person) {
                    return $person->getFullName();
                },
                "required" => false
            ]
        );

        // https://symfony.com/doc/current/best_practices.html#add-form-buttons-in-templates
        /*
        $builder->add(
            "submit",
            SubmitType::class,
            [
                "label" => "Ajouter / modifier"
            ]
        );
        */

        // on demande au formulaire de rajouter une "réaction" un listener
        // lorsque l'evenement "PRE_SET_DATA" aura lieu, on executera la fonction anonyme suivante
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA, 
            function (FormEvent $event) {

                // l'evenement de type FormEvent contient la donnée manipulée par le formulaire
                // la mathode getData() de l'evenement me permet de récupérer la donnée
                // puisque je suis dans un formulaire qui gère des TvShow
                // getData va me renvoyer le TvShow géré par ce formulaire.
                // (le tvShow que l'on a injecté dans ce formulaire lors de sa création dans le controller avec le createForm(FormType::class, $data))
                /** @var TvShow $tvShow */
                $tvShow = $event->getData();

                // l'evenement contient également le formulaire
                $form = $event->getForm();

                // je crée le chmaps title au moment où le formulaire recoit l'objet
                // qu'il doit manipuler
                // si c'est un tvShow existant (qui à un ID) alors le champs sera
                // disabled
                $form->add(
                    "title",
                    TextType::class,
                    [
                        "label" => "Titre de la série",
                        "disabled" => !empty($tvShow->getId())
                    ]
                );
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TvShow::class,
        ]);
    }
}