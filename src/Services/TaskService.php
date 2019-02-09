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

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Task $task
     * @return Task
     */
    public function saveNeTaskService(Task $task): Task
    {
        $this->manager->persist($task);
        $this->manager->flush();

        return $task;

    }
}