<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Client')
        ->setEntityLabelInPlural('Clients')
        ;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::DELETE)
            ->disable(Action::NEW, Action::EDIT)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname', 'First Name'),
            TextField::new('lastname', 'Last Name'),
            TextField::new('email', 'Email'),
            TextField::new('userPhone', 'Phone')->hideOnForm(),
            CollectionField::new('userAddresses', 'Adresses')
            ->hideOnForm()
            ->hideOnDetail()
            ->hideOnIndex(),
            ArrayField::new('userAddresses', 'Adresses')
            ->hideOnForm()
            ->hideOnIndex(),
            CollectionField::new('userPayments', 'Payments')
            ->hideOnForm()
            ->hideOnDetail()
            ->hideOnIndex(),
            ArrayField::new('userPayments', 'Payments')
            ->hideOnForm()
            ->hideOnIndex(),
            DateTimeField::new('createdAt', 'Created At')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Updated At')->hideOnForm(),
        ];
    }

}
