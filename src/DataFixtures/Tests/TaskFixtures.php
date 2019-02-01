<?php

namespace App\DataFixtures\Tests;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $task = (new Task())
            ->setTitle('Une Tache de test')
            ->setContent('Le Contenu de ma tache')
            ->setCreatedAt(new \DateTime());

        $manager->persist($task);
        $manager->flush();
    }
}
