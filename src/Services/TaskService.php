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

class TaskService
{
    private $manager;
    private $userService;

    public function __construct(ObjectManager $manager, UserService $userService)
    {
        $this->manager     = $manager;
        $this->userService = $userService;
    }

    /**
     * @param Task $task
     * @return Task
     */
    public function createNewTask(Task $task): Task
    {
        $user  = $this->userService->getCurrentUser();
        if (null === $user) {
            $user = $this->userService->getAnonymousUser();
        }

        $task->setUser($user);
        $this->manager->persist($task);
        $this->manager->flush();

        return $task;
    }

    public function deleteTask(Task $task)
    {
        // WE GET THE CURRENT USER
        $currentUser = $this->userService->getCurrentUser();
        //dd($currentUser);
    }
}
