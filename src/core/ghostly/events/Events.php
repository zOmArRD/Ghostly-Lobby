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

namespace core\ghostly\events;

use core\ghostly\GExtension;
use core\ghostly\Ghostly;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginManager;

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