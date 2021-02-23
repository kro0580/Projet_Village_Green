<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Livreur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user=$options['user'];
        $builder
            ->add('adresseLiv', EntityType::class, [
                'label'=>false,
                'required'=>true,
                'multiple'=>false,
                'class'=>Adresse::class,
                'expanded'=>true,
                'choices'=>$user->getCliAdresses()
            ])
            ->add('adresseFact', EntityType::class, [
                'label'=>'Mon adresse de facturation',
                'required'=>true,
                'multiple'=>false,
                'class'=>Adresse::class,
                'expanded'=>true,
                'choices'=>$user->getCliAdresses()
            ])
            ->add('livreur', EntityType::class, [
                'label'=>'Choisir mon livreur',
                'required'=>true,
                'multiple'=>false,
                'class'=>Livreur::class,
                'expanded'=>true
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'VALIDER MA COMMANDE',
                'attr'=>[
                    'class'=>'btn btn-success btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user'=>array()
        ]);
    }
}
