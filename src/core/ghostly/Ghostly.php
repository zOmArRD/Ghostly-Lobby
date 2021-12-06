<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 5/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly;

use core\ghostly\events\EventsManager;
use core\ghostly\network\player\skin\SkinAdapter;
use core\ghostly\task\TaskManager;
use pocketmine\network\mcpe\convert\SkinAdapterSingleton;
use pocketmine\plugin\{PluginBase, PluginLogger};

final class Ghostly extends PluginBase
{
    public static Ghostly $ghostly; public static PluginLogger $logger;

    /**
     * @return Ghostly
     */
    public static function getGhostly(): Ghostly
    {
        return self::$ghostly;
    }

    protected function onLoad(): void
    {
        self::$ghostly = $this;
        self::$logger = $this->getLogger();
        self::$logger->info("§a" ."The core is being loaded!");

        GExtension::getResourcesManager()->init();
    }

    protected function onEnable(): void
    {
        /* Mojang Skin Support*/
        SkinAdapterSingleton::set(new SkinAdapter());

        /* It is in charge of registering the plugin events. */
        new EventsManager();

        /* Administrator of all Task. */
        new TaskManager();

        self::$logger->notice(PREFIX . "The core has been fully loaded!");
    }
}