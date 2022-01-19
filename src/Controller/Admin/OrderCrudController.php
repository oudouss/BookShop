<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Order')
        ->setEntityLabelInPlural('Orders')
        ->setNumberFormat('%.2d')
        ;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::DELETE)
            ->disable(Action::NEW)
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'Client')->hideOnForm(),
            TextField::new('userEmail', 'Client Email')->hideOnForm()->hideOnIndex(),
            TextField::new('userAdress', 'Client Adress')->hideOnForm()->hideOnIndex(),
            TextField::new('userPhone', 'Client Phone')->hideOnForm()->hideOnIndex(),
            TextField::new('status', 'Status')->hideOnForm(),
            ChoiceField::new('status', 'Status')
            ->setChoices([
                'PLACED'=>Order::STATUS_PLACED, 
                'PAYED'=>Order::STATUS_PAYED, 
                'INPROGRESS'=>Order::STATUS_INPROGRESS,
                'DELIVERED'=>Order::STATUS_DELIVERED,
             ])
             ->hideOnDetail()
             ->hideOnIndex(),
            NumberField::new('total', 'Total')->hideOnForm(),
            CollectionField::new('items', 'Order Items')
            ->hideOnForm()
            ->hideOnDetail(),
            ArrayField::new('items', 'Order Items')
            ->hideOnForm()
            ->hideOnIndex(),
            DateTimeField::new('createdAt', 'Created At')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Updated At')->hideOnForm(),
        ];
    }

}
