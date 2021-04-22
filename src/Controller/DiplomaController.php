<?php

namespace App\Controller;

use App\Entity\Diploma;
use App\Entity\Task;
use App\Form\DiplomaFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiplomaController extends AbstractController
{
    /**
     * @Route("/diploma", name="diploma")
     */
    public function index(): Response
    {
        return $this->render('diploma/index.html.twig', [
            'controller_name' => 'DiplomaController',
        ]);
    }

    /**
     * @Route("/dodaj-dyplom", name="add_diploma")
     */
    public function addDiploma(EntityManagerInterface $em, Request $request)
    {
        $diploma = new Diploma();
        $task = new Task();
        $diploma->getTasks()->add($task);
        $task1 = new Task();
        $diploma->getTasks()->add($task1);

        $form = $this->createForm(DiplomaFormType::class, $diploma);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->get('tasks')->getData());
            // dd($form->get('tasks'));
            // $diploma->setTitle($form->get('title')->getData());
            // $diploma->setChild($form->get('child')->getData());
            $diploma = $form->getData();
            $em->persist($diploma);
            foreach ($form->get('tasks')->getData() as $item) {
                // dd($item);

                $item->setDiploma($diploma);
                $em->persist($item);
            }


            // $task->setDiploma($diploma);
            // $task1->setDiploma($diploma);
            // $em->persist($task);
            // $em->persist($task1);

            $em->flush();

            $this->addFlash('success', 'Dyplom został dodany');
            return $this->redirectToRoute('add_diploma');
        }

        return $this->render('diploma/add.html.twig', [
            'addDiplomaForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/usun-dyplom/{id}", name="delete_diploma")
     */
    public function delete(int $id, Diploma $diploma, EntityManagerInterface $em)
    {
        $em->remove($diploma);
        $em->flush();

        $this->addFlash('success', 'Dyplom został usunięty');
        return $this->redirectToRoute('user_home');
    }
}
