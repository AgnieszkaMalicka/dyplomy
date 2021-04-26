<?php

namespace App\Controller;

use App\Entity\Diploma;
use App\Entity\Task;
use App\Factory\DiplomaFactory;
use App\Form\DiplomaFormType;
use App\Form\DiplomaSimilarFormType;
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
            $diploma = $form->getData();
            $em->persist($diploma);
            foreach ($form->get('tasks')->getData() as $item) {
                $item->setDiploma($diploma);
                $em->persist($item);
            }

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

    /**
     * @Route("/edytuj-dyplom/{id}", name="edit_diploma")
     */
    public function editDiploma(Diploma $diploma, EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(DiplomaFormType::class, $diploma);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $diploma = $form->getData();
            $em->persist($diploma);
            foreach ($form->get('tasks')->getData() as $item) {
                $item->setDiploma($diploma);
                $em->persist($item);
            }

            $em->flush();

            $this->addFlash('success', 'Dyplom został zedytowany');
        }

        return $this->render('diploma/edit.html.twig', [
            'editDiplomaForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/pokaz-dyplom/{id}", name="show_diploma")
     */
    public function showDiploma(Diploma $diploma, EntityManagerInterface $em, Request $request, DiplomaFactory $diplomaFactory)
    {
        $form = $this->createForm(DiplomaSimilarFormType::class, $diploma);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $diplomaAndTasks = $diplomaFactory->createSimilarDiploma($form->getData());

            $em->refresh($diploma);
            $em->detach($diplomaAndTasks[0]);

            $em->persist($diplomaAndTasks[0]);
            foreach ($diplomaAndTasks[1] as $item) {
                $em->detach($item);
                $em->persist($item);
            }
            $em->flush();

            $this->addFlash('success', 'Dyplom został pomyślnie skopiowany.');
        }

        return $this->render('diploma/show.html.twig', ['diploma' => $diploma, 'similarDiplomaForm' => $form->createView()]);
    }
}
