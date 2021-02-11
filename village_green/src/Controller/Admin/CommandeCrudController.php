<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use function Sodium\add;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->add('index', 'detail');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('cmd_id', 'Id'),
            DateTimeField::new('cmd_date', 'Date de Commande'),
            PercentField::new('cmd_reduc', 'Reduction'),
            TextField::new('cmd_cli_adresse_liv', 'Adresse de livraison'),
            TextField::new('cmd_cli_cp_liv', 'CP de livraison'),
            TextField::new('cmd_cli_ville_liv', 'Ville de livraison'),
            NumberField::new('cmd_cli_coeff', 'Coefficient du Client'),
            BooleanField::new('cmd_payer', 'Payer ?'),
            TextField::new('cmd_liv_nom', 'Livreur'),
            MoneyField::new('cmd_liv_prix', 'Prix de livraison')->setCurrency('EUR')
        ];
    }

}
