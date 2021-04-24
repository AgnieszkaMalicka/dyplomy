<?php

namespace App\Controller;

use App\Entity\Diploma;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/usun-task/{id}", name="delete_task")
     */
    public function delete(Task $task, EntityManagerInterface $em)
    {
        $diploma = $task->getDiploma();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'Task został usunięty');
        return $this->redirectToRoute('edit_diploma', ['id' => $diploma->getId()]);
    }

    /**
     * @Route("/wykonaj-task/{id}", name="capture_task")
     */
    public function capture(Task $task, EntityManagerInterface $em)
    {
        $diploma = $task->getDiploma();

        $task->setMadeAt(new \DateTime());

        $em->persist($task);
        $em->flush();

        $this->addFlash('success', 'Task został wykonany');
        return $this->redirectToRoute('edit_diploma', ['id' => $diploma->getId()]);
    }
}
