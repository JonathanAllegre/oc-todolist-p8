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

    /**
     * DELETE TASK  AFTER CHECK USER
     * @param Task $task
     * @return bool
     */
    public function deleteTask(Task $task)
    {
        // CHECK IF USER LOGGED IN == TASK USER
        if ($this->isUserCanDeleteThisTask($task)) {
            $this->manager->remove($task);
            $this->manager->flush();

            return true;
        }

        return false;
    }

    /**
     * CHECK IF LOGGED USER  = TASK USER
     * @param Task $task
     * @return bool
     */
    protected function isUserCanDeleteThisTask(Task $task): bool
    {
        // GET CURRENT USER
        $currentUser = $this->userService->getCurrentUser();

        // GET TASK USER
        $taskUser = $task->getUser();

        if (null !== $currentUser && $currentUser->getId() === $taskUser->getId()) {
            return true;
        }

        return false;
    }
}
