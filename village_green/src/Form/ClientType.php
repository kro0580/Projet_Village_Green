<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cliNom', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'col-auto col-form-label col-form-label-sm',
                ],
                'help' => 'Indiquez ici votre nom',
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'form-control form-control-sm',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ']),
                    new Regex([
                            'pattern' => '/^[A-Za-z0-9\/éèàçâêûîôäëüïö\:\_\'\-\s]+$/',
                        'message' => 'Caratère(s) non valide(s)'
                    ]),
                ]
            ])
            ->add('cliPrenom', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'col-auto col-form-label col-form-label-sm',
                ],
                'help' => 'Indiquez ici votre prénom',
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => 'form-control form-control-sm',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ']),
                    new Regex([
                            'pattern' => '/^[A-Za-z0-9\/éèàçâêûîôäëüïö\:\_\'\-\s]+$/',
                        'message' => 'Caratère(s) non valide(s)'
                    ]),
                ]
            ])
            ->add('cliEmail', EmailType::class, [
                'label' => 'E-mail',
                'label_attr' => [
                    'class' => 'col-auto col-form-label col-form-label-sm',
                ],
                'help' => 'Indiquez ici votre e-mail',
                'attr' => [
                    'placeholder' => 'Votre e-mail',
                    'class' => 'form-control form-control-sm',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ']),
                    new Regex([
                        'pattern' => '/^[\w\-.]+@([\w\-]+.)+[\w\-]{2,4}$/',
                        'message' => 'Caratère(s) non valide(s)'
                    ]),
                ]
            ])
            ->add('cliPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'col-auto col-form-label col-form-label-sm',
                ],
                'help' => 'Indiquez ici votre mot de passe',
                'attr' => [
                    'placeholder' => 'Votre mot de passe',
                    'class' => 'form-control form-control-sm',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ']),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\w).{8,}$/',
                        'message' => 'Caratère(s) non valide(s)'
                    ]),
                ]
            ])
            ->add('cliConfPassword', PasswordType::class, [
                'label' => 'Confirmation du mot de passe',
                'label_attr' => [
                    'class' => 'col-auto col-form-label col-form-label-sm',
                ],
                'help' => 'Indiquez ici votre confirmation de mot de passe',
                'attr' => [
                    'placeholder' => 'Confirmez votre mot de passe',
                    'class' => 'form-control form-control-sm',
                ],
            ])
            ->add('cliAdresse', TextType::class, [
                'label' => 'Adresse',
                'label_attr' => [
                    'class' => 'col-auto col-form-label col-form-label-sm',
                ],
                'help' => 'Indiquez ici votre adresse',
                'attr' => [
                    'placeholder' => 'Ex : 55 rue Dufour',
                    'class' => 'form-control form-control-sm',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ']),
                    new Regex([
                        'pattern' => '/^([0-9a-zA-Z\'àâéèêôùûçÀÂÉÈÔÙÛÇ\s\-]{1,50})$/',
                        'message' => 'Caratère(s) non valide(s)'
                    ]),
                ]
            ])
            ->add('cliCp', TextType::class, [
                'label' => 'Code postal',
                'label_attr' => [
                    'class' => 'col-auto col-form-label col-form-label-sm',
                ],
                'help' => 'Indiquez ici votre code postal',
                'attr' => [
                    'placeholder' => 'Ex : 75000',
                    'class' => 'form-control form-control-sm',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ']),
                    new Regex([
                        'pattern' => '/^[0-9]{5}$/',
                        'message' => 'Caratère(s) non valide(s)'
                    ]),
                ]
            ])
            ->add('cliVille', TextType::class, [
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'col-auto col-form-label col-form-label-sm',
                ],
                'help' => 'Indiquez ici votre ville',
                'attr' => [
                    'placeholder' => 'Ex : PARIS',
                    'class' => 'form-control form-control-sm',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ']),
                    new Regex([
                            'pattern' => '/^[A-Za-z0-9\/éèàçâêûîôäëüïö\:\_\'\-\s]+$/',
                        'message' => 'Caratère(s) non valide(s)'
                    ]),
                ]
            ])
            //->add('cliRegl')
            //->add('cliCateg')
            //->add('cliCoeff')
            //->add('cliCom')
            //->add('passeCmd')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
