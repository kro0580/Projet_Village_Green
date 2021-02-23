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
            ->add('adr_num_rue', TextType::class, [
                'label'=>'Adresse',
                'attr'=>[
                    'placeholder'=>'2 rue de la Paix '
                ]
            ])
            ->add('adr_cp', TextType::class, [
                'label'=>'Code Postal',
                'attr'=>[
                    'placeholder'=>'80000'
                ]
            ])
            ->add('adr_ville', TextType::class, [
                'label'=>'Ville',
                'attr'=>[
                    'placeholder'=>'Amiens'
                ]
            ])
            ->add('adr_pays', CountryType::class, [
                'label'=>'Pays'
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'AJOUTER MON ADRESSE',
                'attr'=>[
                    'class'=>'btn btn-info'
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
