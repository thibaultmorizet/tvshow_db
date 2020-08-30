<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'number',
            IntegerType::class,
            [
                "label" => "Numéro"
            ]
        );

        $builder->add(
            'year',
            IntegerType::class,
            [
                "label" => "Année"
            ]
        );

        $builder->add(
            'episodes',
            CollectionType::class, 
            [
                "label" => "Episodes",
                "entry_type" => EpisodeType::class,
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => "sub-form"
                    ]
                ],
                'allow_add' => true,
                'by_reference' => false,
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
