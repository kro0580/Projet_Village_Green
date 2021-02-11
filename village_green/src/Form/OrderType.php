<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Livreur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'label'=>'Veillez choisir votre adresse de facturation',
                'required'=>true,
                'multiple'=>false,
                'class'=>Adresse::class,
                'expanded'=>true,
                'choices'=>$user->getCliAdresses()
            ])
            ->add('livreur', EntityType::class, [
                'label'=>'Veillez choisir votre livreur',
                'required'=>true,
                'multiple'=>false,
                'class'=>Livreur::class,
                'expanded'=>true
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Valider ma commande',
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
