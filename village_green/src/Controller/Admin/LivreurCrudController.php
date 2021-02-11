<?php

namespace App\Controller\Admin;

use App\Entity\Livreur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LivreurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livreur::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('liv_nom', 'Nom'),
            TextareaField::new('liv_description', 'Description'),
            MoneyField::new('liv_prix', 'Prix')->setCurrency('EUR')
        ];
    }

}
