<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-02-10
 * Time: 10:24
 */

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $task = (new Task())
            ->setContent('Une tache')
            ->setTitle('Un titre')
            ->setCreatedAt(new \DateTime());

        $manager->persist($task);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['devprod'];
    }
}
