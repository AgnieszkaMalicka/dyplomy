<?php

namespace App\Factory;

use App\Entity\Child;
use App\Entity\Diploma;
use App\Entity\Task;
use App\Repository\DiplomaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Diploma|Proxy createOne(array $attributes = [])
 * @method static Diploma[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Diploma|Proxy find($criteria)
 * @method static Diploma|Proxy findOrCreate(array $attributes)
 * @method static Diploma|Proxy first(string $sortedField = 'id')
 * @method static Diploma|Proxy last(string $sortedField = 'id')
 * @method static Diploma|Proxy random(array $attributes = [])
 * @method static Diploma|Proxy randomOrCreate(array $attributes = [])
 * @method static Diploma[]|Proxy[] all()
 * @method static Diploma[]|Proxy[] findBy(array $attributes)
 * @method static Diploma[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Diploma[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static DiplomaRepository|RepositoryProxy repository()
 * @method Diploma|Proxy create($attributes = [])
 */
final class DiplomaFactory extends ModelFactory
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    public function createSimilarDiploma(Diploma $diploma)
    {

        $oldDiploma = $this->em->getRepository(Diploma::class)->getDiplomaWithTasks($diploma);
        $tasks = $oldDiploma[0]->getTasks();

        $similarDiploma = new Diploma();
        $similarDiploma->setTitle($oldDiploma[0]->getTitle());
        $similarDiploma->setChild($oldDiploma[0]->getChild());
        $similarDiploma->setCapturedAt(NULL);

        $newTasks = [];
        foreach ($tasks as $task) {
            $newItem = new Task();
            $newItem->setDiploma($similarDiploma);
            $newItem->setDescription($task->getDescription());
            $newTasks[] = $newItem;
        }

        return [$similarDiploma, $newTasks];

        // $newDiploma = clone $diploma; //to nie działało dlatego powyżej własna funkcja getDiplomaWithTasks       
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://github.com/zenstruck/foundry#model-factories)
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Diploma $diploma) {})
        ;
    }

    protected static function getClass(): string
    {
        return Diploma::class;
    }
}
