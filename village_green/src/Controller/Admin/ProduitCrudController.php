<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('pro_lib', 'Libellé'),
            TextField::new('pro_s_rub', 'Rubrique'),
            TextField::new('pro_descr', 'Description'),
            MoneyField::new('pro_prix_achat', 'Prix')->setCurrency('EUR'),
            NumberField::new('pro_stock', 'Stock'),
            BooleanField::new('pro_actif', 'Actif ?'),
        ];
    }
    
}