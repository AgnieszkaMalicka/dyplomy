<?php

namespace App\Service;

use App\Entity\Diploma;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class CapturedDiplomaDeterminator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function ifAllTasksInDiplomaAreReady(Diploma $diploma): bool
    {
        $result = $this->em->getRepository(Task::class)->getCountUncapturedTasksInDiploma($diploma);

        return count($result) > 0 ? false : true;
    }
}
