<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Category;
use App\Controller\Admin\OrderCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\CategoryCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(OrderCrudController::class)->generateUrl());

    }
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="logo.png"/>');
            // ->renderSidebarMinimized();
            
            
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Website'),
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class),
            MenuItem::linkToCrud('Books', 'fa fa-file-text', Book::class),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('Clients', 'fa fa-user', User::class),
            // MenuItem::linkToCrud('Admins', 'fa fa-user', Admin::class),

            MenuItem::section('Orders'),
            MenuItem::linkToCrud('Orders', 'fa fa-shopping-cart', Order::class),

        ];
    }
}
