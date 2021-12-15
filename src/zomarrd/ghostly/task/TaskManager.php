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


final class TaskManager extends TaskBase
{
    public function __construct()
    {
        $this->load();
    }

    /**
     * @return void
     */
    private function load(): void
    {

    }
}