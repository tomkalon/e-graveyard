<?php

namespace App\Controller\Admin;

use App\Entity\Grave;
use App\Entity\Graveyard;
use App\Entity\Person;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
         return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Panel administratora')
            ->setLocales([
                'pl' => '🇵🇱 Polski'
            ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('DASHBOARD', 'fa fa-home');
        yield MenuItem::section('GRAVEYARD');
        yield MenuItem::linkToCrud('GRAVEYARDS', 'fas fa-globe', Graveyard::class);
        yield MenuItem::linkToCrud('DEAD', 'fas fa-address-book', Person::class);
        yield MenuItem::linkToCrud('GRAVES', 'fas fa-map-marker', Grave::class);
        yield MenuItem::section('USERS');
        yield MenuItem::linkToCrud('USERS', 'fas fa-user-circle', User::class);
        yield MenuItem::linkToUrl('REGISTER', 'fas fa-user-plus', '/register');
        yield MenuItem::section('OTHER');
        yield MenuItem::linkToUrl('HOME_SITE', 'fas fa-user-plus', '/');
    }
}
