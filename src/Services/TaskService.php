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

class TaskService
{

    private $manager;
    private $security;

    public function __construct(Security $security, ObjectManager $manager)
    {
        $this->manager  = $manager;
        $this->security = $security;
    }

    public function createNewTask(Task $task): Task
    {
        $user = $this->security->getUser();

        //dd($user);

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