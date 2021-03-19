<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adr_num_rue', TextType::class, [
                'label'=>'Adresse',
                'help' => 'Indiquez ici votre adresse',
                'attr'=>[
                    'placeholder'=>'2 rue de la Paix '
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ']),
                    new Regex([
                        'pattern' => '/^([0-9a-zA-Z\'àâéèêôùûçÀÂÉÈÔÙÛÇ\s\-]{1,50})$/',
                        'message' => 'Caratère(s) non valide(s)'
                    ]),
                ]
            ])
            ->add('adr_cp', TextType::class, [
                'label'=>'Code Postal',
                'help' => 'Indiquez ici votre code postal',
                'attr'=>[
                    'placeholder'=>'80000'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ']),
                    new Regex([
                        'pattern' => '/^[0-9]{5}$/',
                        'message' => 'Caratère(s) non valide(s)'
                    ]),
                ]
            ])
            ->add('adr_ville', TextType::class, [
                'label'=>'Ville',
                'help' => 'Indiquez ici votre ville',
                'attr'=>[
                    'placeholder'=>'Amiens'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ']),
                    new Regex([
                        'pattern' => '/^[A-Za-z0-9\/éèàçâêûîôäëüïö\:\_\'\-\s]+$/',
                        'message' => 'Caratère(s) non valide(s)'
                    ]),
                ]
            ])
            ->add('adr_pays', CountryType::class, [
                'label'=>'Pays',
                'help' => 'Indiquez ici votre pays',
                'placeholder'=>'Sélectionner votre pays',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ']),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'VALIDER MON ADRESSE',
                'attr'=>[
                    'class'=>'btn btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Se référe au fichier AdresseType.php pour l'affichage des données dans le formulaire
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
