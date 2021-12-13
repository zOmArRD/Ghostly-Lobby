<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 25/11/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\task;

use pocketmine\scheduler\Task;
use pocketmine\scheduler\TaskHandler;
use pocketmine\scheduler\TaskScheduler;
use zomarrd\ghostly\GExtension;

abstract class TaskBase
{
    /**
     * @param Task $task
     * @param int  $period
     *
     * @return TaskHandler
     */
    public function registerTask(
        Task $task,
        int  $period
    ): TaskHandler
    {
        return $this->getTaskScheduler()->scheduleRepeatingTask($task, $period);
    }

    /**
     * @return TaskScheduler
     */
    public function getTaskScheduler(): TaskScheduler
    {
        return GExtension::getTaskScheduler();
    }
}