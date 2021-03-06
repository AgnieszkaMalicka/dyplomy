<?php

namespace App\Controller;

use App\Entity\Child;
use App\Form\ChildFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChildController extends AbstractController
{
    /**
     * @Route("/dodaj-dziecko", name="add_child")
     */
    public function addChild(EntityManagerInterface $em, Request $request)
    {
        $child = new Child();
        $form = $this->createForm(ChildFormType::class, $child);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $child->setName($form->get('name')->getData());
            $child->setAge($form->get('age')->getData());
            $child->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($child);
            $entityManager->flush();

            $this->addFlash('success', 'Dziecko zostało dodane');
            return $this->redirectToRoute('user_home');
        }

        return $this->render('child/add.html.twig', [
            'addChildForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/usun-dziecko/{id}", name="delete_child")
     */
    public function delete(int $id, Child $child, EntityManagerInterface $em)
    {
        $em->remove($child);
        $em->flush();

        $this->addFlash('success', 'Dziecko zostało usunięte');
        return $this->redirectToRoute('user_home');
    }

    /**
     * @Route("/edytuj-dziecko/{id}", name="edit_child")
     */
    public function editChild(Child $child, EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(ChildFormType::class, $child);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //zamiast tych dwóch linijek może być $child = $form->getData();
            $child->setName($form->get('name')->getData());
            $child->setAge($form->get('age')->getData());

            // $entityManager = $this->getDoctrine()->getManager(); //EntityManager przekazujemy jako argument więc nie trzeba tej linijki
            $em->persist($child);
            $em->flush();

            $this->addFlash('success', 'Dziecko zostało zedytowane');
            return $this->redirectToRoute('edit_child', ['id' => $child->getId()]);
        }

        return $this->render('child/edit.html.twig', [
            'editChildForm' => $form->createView(),
        ]);
    }
}
