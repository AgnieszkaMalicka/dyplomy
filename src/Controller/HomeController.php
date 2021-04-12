<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function project(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/admin", name="admin_home")
     */
    public function admin_home(): Response
    {
        return $this->render('admin/index.html.twig', []);
    }

    /**
     * @Route("/profil/home", name="user_home")
     */
    public function user_home(): Response
    {
        return $this->render('user/index.html.twig', []);
    }
}
