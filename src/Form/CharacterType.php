<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            "name",
            TextType::class,
            [
                "label" => "Nom du personnage"
            ]
        );

        $builder->add(
            "actors",
            EntityType::class,
            [
                "class" => Person::class,
                "choice_label" => function (Person $person) {
                    return $person->getFullName();
                },
                "required" => false,
                "multiple" => true,
                "expanded" => true,
                "attr" => [
                    "class" => "compact-select-list"
                ]
            ]
        );

        $builder->add(
            "picture",
            FileType::class,
            [
                "label" => "Image",
                "mapped" => false,
                "required" => false,
                "constraints" => [
                    new Image([
                        'maxSize' => '1024k'
                    ])
                ]
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}
