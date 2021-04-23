<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Diploma;
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
    public function user_home(EntityManagerInterface $em): Response
    {
        $childrenList = $this->getUser()->getChildren();

        $repository = $em->getRepository(Diploma::class);

        foreach ($childrenList as $child) {
            $child->diplomasCaptured = $repository->getCapturedDiplomas($child->getId());
            $child->diplomasUnCaptured = $repository->getUnCapturedDiplomas($child->getId());
        }

        return $this->render('user/index.html.twig', ['childrenList' => $childrenList]);
    }
}
