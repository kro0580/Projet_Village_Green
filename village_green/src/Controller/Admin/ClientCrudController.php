<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ClientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    // Pour désactiver les actions d'édition et de suppression du Dashboard
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::EDIT, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('cli_nom', 'Nom'),
            TextField::new('cli_prenom', 'Prénom'),
            TextField::new('cli_email', 'Email'),
            TextField::new('cli_adresse', 'Adresse'),
            TextField::new('cli_cp', 'CP'),
            TextField::new('cli_ville', 'Ville'),
            TextField::new('cli_role', 'Rôle')
        ];
    }
    
}