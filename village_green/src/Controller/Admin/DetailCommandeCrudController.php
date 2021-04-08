<?php

namespace App\Controller\Admin;

use App\Entity\DetailCommande;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DetailCommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DetailCommande::class;
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
            TextField::new('det_cmd_produit', 'Produit'),
            NumberField::new('det_cmd_pro_qte', 'Quantité'),
            MoneyField::new('prix', 'Prix du produit')->setCurrency('EUR'),
            MoneyField::new('prix_total', 'Prix total')->setCurrency('EUR'),
            AssociationField::new('det_cmd_cmd_id', 'Référence'),
        ];
    }
    
}