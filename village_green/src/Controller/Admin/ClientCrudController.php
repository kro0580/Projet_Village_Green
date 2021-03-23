<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Client::class;
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