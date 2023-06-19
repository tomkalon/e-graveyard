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
            ->setTitle('ADMIN_PANEL')
            ->setLocales([
                'pl' => '🇵🇱 Polski'
            ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('DASHBOARD', 'fa fa-home');
        yield MenuItem::linkToCrud('USERS', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('GRAVEYARDS', 'fas fa-list', Graveyard::class);
        yield MenuItem::linkToCrud('DEAD', 'fas fa-list', Person::class);
        yield MenuItem::linkToCrud('GRAVES', 'fas fa-list', Grave::class);
    }
}
