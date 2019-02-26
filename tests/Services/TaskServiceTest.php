<?php

namespace App\Tests\Services;

use App\Entity\Task;
use App\Entity\User;
use App\Services\TaskService;
use App\Services\UserService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskServiceTest extends KernelTestCase
{
    /**
     * @throws \Exception
     */
    public function testCreateNewtask()
    {
        // TEST CREATE TASK WITH USR LOGED IN

        // GET AN USER FOR MOCK
        $user = $this->getAnUserByUsername('userTestOne');
        // MOCK OBJECT MANAGER
        $manager = $this->createMock(ObjectManager::class);
        $manager
            ->expects($this->any())
            ->method('persist')
            ->willReturn(true);
        $manager
            ->method('flush')
            ->willReturn(true);

        // MOCK USERSERVICE
        $userService = $this->createMock(UserService::class);
        $userService
            ->expects($this->any())
            ->method('getCurrentUser')
            ->willReturn($user);

        $taskService = $this->initService(['objectManager' => $manager, 'userService' => $userService]);
        $return = $taskService->createNewTask($this->getParamsForCreateNewTask());

        $this->assertInstanceOf(UserInterface::class, $return->getUser());
        $this->assertEquals('userTestOne', $return->getUser()->getUsername());


        // TEST CREATE TASK WITH NO USER LOGED IN
        // MOCK OBJECT MANAGER
        $manager = $this->createMock(ObjectManager::class);
        $manager
            ->expects($this->any())
            ->method('persist')
            ->willReturn(true);
        $manager
            ->method('flush')
            ->willReturn(true);

        // MOCK USERSERVICE
        $userService = $this->createMock(UserService::class);
        $userService
            ->expects($this->any())
            ->method('getCurrentUser')
            ->willReturn(null);
        $userService
            ->expects($this->any())
            ->method('getAnonymousUser')
            ->willReturn($this->getAnUserByUsername('anonymous'));

        $taskService = $this->initService(['objectManager' => $manager, 'userService' => $userService]);
        $return = $taskService->createNewTask($this->getParamsForCreateNewTask());

        $this->assertInstanceOf(UserInterface::class, $return->getUser());
        $this->assertEquals('anonymous', $return->getUser()->getUsername());
    }

    public function testDeleteTask()
    {
        $taskService = $this->initService();

        //$taskService->deleteTask()


    }

    protected function initService(array $mock = null): TaskService
    {
        $objectManager = $this->getContainer()->get(ObjectManager::class);
        $userService = $this->getContainer()->get(UserService::class);

        if (isset($mock['objectManager'])) {
            $objectManager = $mock['objectManager'];
        }

        if (isset($mock['userService'])) {
            $userService = $mock['userService'];
        }

        $taskService = new TaskService($objectManager, $userService);

        return $taskService;

    }


    protected function getContainer()
    {
        self::bootKernel();
        // gets the special container that allows fetching private services
        $container = self::$container;

        return $container;
    }

    /**
     * @return Task
     * @throws \Exception
     */
    protected function getParamsForCreateNewTask(): Task
    {
        return (new Task())
            ->setUser(null)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Ma Tache de Test qui tes unit')
            ->setContent('Contenu de ma tache de test');
    }

    /**
     * @param string $username
     * @return User
     */
    protected function getAnUserByUsername(string $username): User
    {
        $user = $this
                ->getContainer()
                ->get('doctrine')
                ->getRepository(User::class)
                ->findOneby(['username' => $username]);

        return $user;
    }
}
