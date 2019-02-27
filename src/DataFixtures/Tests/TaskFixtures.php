<?php

namespace App\DataFixtures\Tests;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $task = (new Task())
            ->setTitle('TaskForListAction')
            ->setContent('Le Contenu de ma tache de test')
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getReference(UserFixtures::ANONYMOUS_USER));

        $manager->persist($task);
        $manager->flush();

        $task = (new Task())
            ->setTitle('TaskForEditaction')
            ->setContent('Le Contenu de ma tache de test')
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getReference(UserFixtures::ANONYMOUS_USER));

        $manager->persist($task);
        $manager->flush();

        $task = (new Task())
            ->setTitle('TaskForToggleAction')
            ->setContent('Le Contenu de ma tache de test')
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getReference(UserFixtures::ANONYMOUS_USER));

        $manager->persist($task);
        $manager->flush();

        $task = (new Task())
            ->setTitle('TaskForDeleteAction')
            ->setContent('Le Contenu de ma tache de test')
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getReference(UserFixtures::ANONYMOUS_USER));

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

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
