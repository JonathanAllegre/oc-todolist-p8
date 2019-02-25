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
            ->setTitle('Une Tache de test')
            ->setContent('Le Contenu de ma tache de test')
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getReference(UserFixtures::ANONYMOUS_USER));

        $manager->persist($task);
        $manager->flush();

        $task = (new Task())
            ->setTitle('TaskTestToogle')
            ->setContent('Le Contenu de ma tache de test toggle')
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getReference(UserFixtures::ANONYMOUS_USER));

        $manager->persist($task);
        $manager->flush();

        $task = (new Task())
            ->setTitle('TaskTestEdit')
            ->setContent('Le Contenu de ma tache de test Edit')
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getReference(UserFixtures::ANONYMOUS_USER));

        $manager->persist($task);
        $manager->flush();

        $task = (new Task())
            ->setTitle('TaskTestDelete')
            ->setContent('Le Contenu de ma tache de test Delete')
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getReference(UserFixtures::ANONYMOUS_USER));

        $manager->persist($task);
        $manager->flush();

        // TASK ADMIN
        $task = (new Task())
            ->setTitle('TaskAdmin')
            ->setContent('Le Contenu de ma tache de test Role Admin')
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getReference(UserFixtures::ADMIN_USER));

        $manager->persist($task);
        $manager->flush();

        // TASK USER
        $task = (new Task())
            ->setTitle('TaskUser')
            ->setContent('Le Contenu de ma tache de test Role User')
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getReference(UserFixtures::USER_USER));

        $manager->persist($task);
        $manager->flush();

        // TASK ANONYMOUS
        $task = (new Task())
            ->setTitle('TaskAnonymous')
            ->setContent('Le Contenu de ma tache de test Role Anonyme')
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
