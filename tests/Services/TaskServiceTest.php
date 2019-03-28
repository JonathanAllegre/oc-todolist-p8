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
    public function testCreateNewtask()
    {
        // TEST CREATE TASK WITH USR LOGED IN

        // GET AN USER FOR MOCK
        $user = $this->getAnUserByUsername('user');
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

        // ASSERT TASK NOW HAVE AN USER
        $this->assertInstanceOf(UserInterface::class, $return->getUser());
        $this->assertEquals('user', $return->getUser()->getUsername());


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

        // ASSERT TASK HAVE NOW A ANONYMOUS USER EVEN IF USER IS NOT LOGGED IN
        $this->assertInstanceOf(UserInterface::class, $return->getUser());
        $this->assertEquals('anonymous', $return->getUser()->getUsername());
    }
    public function testDeleteTask()
    {
        $user = $this->createNewUser('usertest', ['ROLE_USER']);
        $task = $this->createNewTask($user, 'le contenu', 'Le Titre');

        ################## TEST WITH TASK USER = LOGGED USER ####################################@
        // MOCK CURRENT USER
        $userservice = $this->createMock(UserService::class);
        $userservice
            ->expects($this->any())
            ->method('getCurrentUser')
            ->willReturn($user);

        // MOCK OBJECT MANAGER
        $manager = $this->createMock(ObjectManager::class);
        $manager
            ->expects($this->any())
            ->method('remove')
            ->willReturn(true);
        $manager
            ->method('flush')
            ->willReturn(true);


        $taskService = $this->initService([
            'userService' => $userservice,
            'objectManager' => $manager
        ]);

        $this->assertTrue($taskService->deleteTask($task));

        ################## TEST WITH TASK USER != LOGGED USER ####################################@
        // MOCK CURRENT USER
        $userservice = $this->createMock(UserService::class);
        $userservice
            ->expects($this->any())
            ->method('getCurrentUser')
            ->willReturn($this->createNewUser('test', ['ROLE_USER']));

        $taskService = $this->initService([
            'userService' => $userservice,
            'objectManager' => $manager
        ]);

        $this->assertFalse($taskService->deleteTask($task));

        ################## TEST WITH NO USER LOGGED IN ####################################@
        // MOCK CURRENT USER
        $userservice = $this->createMock(UserService::class);
        $userservice
            ->expects($this->any())
            ->method('getCurrentUser')
            ->willReturn(null);

        $taskService = $this->initService([
            'userService' => $userservice,
            'objectManager' => $manager
        ]);

        $this->assertFalse($taskService->deleteTask($task));


        ################## TEST WITH  LOGGED USER HAS ADMIN ROLE & TASKUSER = anonymous ####################
        $user = $this->createNewUser('anonymous', ['ROLE_ANONYMOUS']);
        $task = $this->createNewTask($user, 'LeCOntenu', 'LeTitle');

        // MOCK CURRENT USER
        $userservice = $this->createMock(UserService::class);
        $userservice
            ->expects($this->any())
            ->method('getCurrentUser')
            ->willReturn($this->createNewUser('admin', ['ROLE_ADMIN']));
        $userservice
            ->expects($this->any())
            ->method('userHasAdminRole')
            ->willReturn(true);

        $taskService = $this->initService([
            'userService' => $userservice,
            'objectManager' => $manager
        ]);

        $this->assertTrue($taskService->deleteTask($task));


        ################## TEST WITH  LOGGED USER HAVEN'T ADMIN ROLE & TASKUSER = anonymous ####################
        $user = $this->createNewUser('anonymous', ['ROLE_ANONYMOUS']);
        $task = $this->createNewTask($user, 'LeCOntenu', 'LeTitle');

        // MOCK CURRENT USER
        $userservice = $this->createMock(UserService::class);
        $userservice
            ->expects($this->any())
            ->method('getCurrentUser')
            ->willReturn($this->createNewUser('admin', ['ROLE_ADMIN']));
        $userservice
            ->expects($this->any())
            ->method('userHasAdminRole')
            ->willReturn(false);

        $taskService = $this->initService([
            'userService' => $userservice,
            'objectManager' => $manager
        ]);

        $this->assertFalse($taskService->deleteTask($task));
    }

    // INIT CLASS
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

    // PARAMETERS
    protected function getParamsForCreateNewTask(): Task
    {
        return (new Task())
            ->setUser(null)
            ->setCreatedAt(new \DateTime())
            ->setTitle('Ma Tache de Test qui tes unit')
            ->setContent('Contenu de ma tache de test');
    }
    protected function getAnUserByUsername(string $username): User
    {
        $user = $this
                ->getContainer()
                ->get('doctrine')
                ->getRepository(User::class)
                ->findOneby(['username' => $username]);

        return $user;
    }

    // MOCK
    protected function createNewTask($user, $content, $title)
    {
        $task = (new Task())
            ->setUser($user)
            ->setContent($content)
            ->setTitle($title);

        return $task;
    }
    protected function createNewUser($username, $roles): User
    {
        $user = (new User())
            ->setRoles($roles)
            ->setEmail($username.'@test.com')
            ->setUsername($username);

        return $user;
    }
}
