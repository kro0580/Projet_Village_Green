<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adr_num_rue',TextType::class, [
                'label'=>'Numéro, Rue',
                'attr'=>[
                    'placeholder'=>'3, rue de toto'
                ]
            ])
            ->add('adr_cp',TextType::class, [
                'label'=>'Code Postal',
                'attr'=>[
                    'placeholder'=>'80000'
                ]
            ])
            ->add('adr_ville',TextType::class, [
                'label'=>'Ville',
                'attr'=>[
                    'placeholder'=>'Amiens'
                ]
            ])
            ->add('adr_pays',CountryType::class, [
                'label'=>'Pays'
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Ajouter mon adresse',
                'attr'=>[
                    'class'=>'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
