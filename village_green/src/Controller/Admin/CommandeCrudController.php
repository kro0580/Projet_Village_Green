<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
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
            IdField::new('cmd_id', 'ID'),
            DateTimeField::new('cmd_date', 'Date de Commande'),
            TextField::new('cmd_cli_adresse_liv', 'Adresse de livraison'),
            TextField::new('cmd_cli_cp_liv', 'CP de livraison'),
            TextField::new('cmd_cli_ville_liv', 'Ville de livraison'),
            BooleanField::new('cmd_payer', 'Commande payée ?'),
            TextField::new('cmd_liv_nom', 'Livreur'),
            MoneyField::new('cmd_liv_prix', 'Prix de livraison')->setCurrency('EUR')
        ];
    }

}