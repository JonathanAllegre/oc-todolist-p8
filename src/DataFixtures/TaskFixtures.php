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
    private $manager;

    /**
     * TaskFixtures constructor.
     * @param Security $security
     * @param ObjectManager $manager
     */
    public function __construct(Security $security, ObjectManager $manager)
    {
        $this->security = $security;
        $this->manager  = $manager;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->createNewTask('Une Tâche de Test', 'Le contenue de ma tâche');
        $this->createNewTask('Une 2nd Tâche de Test', 'Le contenue de ma 2nd Tâche');
    }

    /**
     * @param string $title
     * @param string $content
     * @throws \Exception
     */
    public function createNewTask(string $title, string $content)
    {
        $task = new Task();
        $task
            ->setTitle($title)
            ->setContent($content)
            ->setUser($this->getReference(UserFixtures::ANONYMOUS_USER))
            ->setCreatedAt(new \DateTime());

        $this->manager->persist($task);
        $this->manager->flush();
    }

    /**
     * @return array
     */
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
    public function getDependencies(): array
    {
        return [
           UserFixtures::class
        ];
    }
}
