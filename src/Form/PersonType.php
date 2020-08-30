<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            "firstName",
            TextType::class,
            [
                "label" => "Prénom de l'acteur"
            ]
        );

        $builder->add(
            "lastName",
            TextType::class,
            [
                "label" => "Nom de l'acteur"
            ]
        );

        $builder->add(
            "birthDate",
            DateType::class,
            [
                "label" => "Date de naissance",
                "required" => false,
                "widget" => "single_text"
            ]
        );

        $builder->add(
            "gender",
            ChoiceType::class,
            array(
                'choices'  => array(
                    'Homme' => "male" ,
                    'Femme' => "female"
                ),
            )
        );

    }
}