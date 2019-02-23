<?php

namespace App\DataFixtures\Tests;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $task = (new Task())
            ->setTitle('Une Tache de test')
            ->setContent('Le Contenu de ma tache de test')
            ->setCreatedAt(new \DateTime());

        $manager->persist($task);
        $manager->flush();

        $task = (new Task())
            ->setTitle('TaskTestToogle')
            ->setContent('Le Contenu de ma tache de test toggle')
            ->setCreatedAt(new \DateTime());

        $manager->persist($task);
        $manager->flush();

        $task = (new Task())
            ->setTitle('TaskTestEdit')
            ->setContent('Le Contenu de ma tache de test Edit')
            ->setCreatedAt(new \DateTime());

        $manager->persist($task);
        $manager->flush();

        $task = (new Task())
            ->setTitle('TaskTestDelete')
            ->setContent('Le Contenu de ma tache de test Delete')
            ->setCreatedAt(new \DateTime());

        $manager->persist($task);
        $manager->flush();
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['test'];
    }
}
