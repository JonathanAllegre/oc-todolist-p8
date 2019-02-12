<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 2019-02-08
 * Time: 16:33
 */

namespace App\Services;

use App\Entity\Task;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskService
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager  = $manager;
    }

    public function createNewTask(UserInterface $user, Task $task): Task
    {
        $task->setUser($user);
        $this->saveNewTaskService($task);

        return $task;
    }

    /**
     * @param Task $task
     */
    public function saveNewTaskService(Task $task): void
    {
        $this->manager->persist($task);
        $this->manager->flush();
    }
}
