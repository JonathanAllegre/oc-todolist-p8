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
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Security;

class TaskFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function load(ObjectManager $manager)
    {
        $task = (new Task())
            ->setContent('Une tache')
            ->setTitle('Un titre')
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getReference(UserFixtures::ANONYMOUS_USER));

        $manager->persist($task);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['devprod'];
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
