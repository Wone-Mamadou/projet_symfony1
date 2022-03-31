<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Commentaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label'=> 'Pseudo',
                'attr'=> [
                    'placeholder' => 'Pseudo'
                ]
            ])
            ->add('contenus', TextareaType::class, [
                'label'=> 'Commentaire',
                'attr'=> [
                    'placeholder' => 'Veuillez rediger votre commentaire'
                    ]
            ])
            
            // ->add('article', EntityType::class, [
            //     'class'=> Article::class,
            //     'data'=> function ($articles){
            //         return $articles -> getId();
            //     }
            // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
