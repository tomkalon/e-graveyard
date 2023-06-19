<?php

namespace App\Controller\Admin;

use App\Entity\Grave;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GraveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Grave::class;
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
