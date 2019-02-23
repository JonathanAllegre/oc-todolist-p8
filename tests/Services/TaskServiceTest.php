<?php

namespace App\Tests\Services;

use App\Entity\Task;
use App\Entity\User;
use App\Services\TaskService;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Tests\Command\CacheClearCommand\Fixture\TestAppKernel;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskServiceTest extends KernelTestCase
{
    public function testCreateNewtask()
    {
        $user = $this
            ->getContainer()
            ->get('doctrine')->getRepository(User::class)
            ->findOneByUsername('jonathan');

        // MOCK OBJECT MANAGER
        $manager = $this->createMock(ObjectManager::class);
        $manager
            ->expects($this->any())
            ->method('persist')
            ->willReturn(true);
        $manager
            ->method('flush')
            ->willReturn(true);

        $taskService = new TaskService($manager);
        $return = $taskService->createNewTask($user, $this->getParamsForCreateNewTask());

        $this->assertInstanceOf(UserInterface::class, $return->getUser());
        $this->assertEquals('jonathan', $return->getUser()->getUsername());
    }

    private function getContainer()
    {
        self::bootKernel();
        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();
        // gets the special container that allows fetching private services
        $container = self::$container;

        return $container;
    }

    // PARAMS
    public function getParamsForCreateNewTask(): Task
    {
        return (new Task())
            ->setUser(null)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Ma Tache de Test qui tes unit')
            ->setContent('Contenu de ma tache de test');
    }
}
