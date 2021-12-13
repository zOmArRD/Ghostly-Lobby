<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 2/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\events;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginManager;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\Ghostly;

abstract class Events
{

    /**
     * @param Listener $event
     */
    public function register(Listener $event): void
    {
        $this->getPluginManager()->registerEvents($event, Ghostly::getGhostly());
    }

    /**
     * @return PluginManager
     */
    private function getPluginManager(): PluginManager
    {
        return GExtension::getPluginManager();
    }

    abstract public function loadEvents(): void;
}