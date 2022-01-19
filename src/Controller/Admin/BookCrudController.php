<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Book')
        ->setEntityLabelInPlural('Books')
        ->setNumberFormat('%.2d')
        ;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Title'),
            ImageField::new('image', 'Image')
            ->setBasePath($this->getParameter('app.path.book_images'))
            ->hideOnForm(),
            TextField::new('author', 'Author'),
            TextField::new('description', 'Description'),
            NumberField::new('price', 'Price'),
            IntegerField::new('stock', 'Stock'),
            Field::new('imageFile', 'Image')
            ->setFormType(VichImageType::class)
            ->setFormTypeOption('allow_delete', false)
            ->hideOnIndex()
            ->hideOnDetail(),
            AssociationField::new('category', 'Category')
            ->autocomplete(),
        ];
    }
    
}
