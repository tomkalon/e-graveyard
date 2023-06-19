<?php

namespace App\Controller\Admin;

use App\Entity\Graveyard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GraveyardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Graveyard::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
