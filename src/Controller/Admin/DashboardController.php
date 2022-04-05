<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Controller\Admin\UserCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;


class DashboardController extends AbstractDashboardController
{
    // ...

    #[Route('/admin', name: "app_admin")]
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $this->redirectToRoute('app_login');
        }
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);


        // Option 1. Make your dashboard redirect to the same page for all users
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }
    /*
    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::section('Users'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
        ];
    }
    */
}
