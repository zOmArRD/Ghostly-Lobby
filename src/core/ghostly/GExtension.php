<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 25/11/2021
 *
 * Copyright © 2021 Ghostly GExtension - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly;

use core\ghostly\network\resources\ResourcesManager;
use pocketmine\plugin\PluginManager;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server;
use pocketmine\world\WorldManager;

final class GExtension
{
    /**
     * @return Ghostly
     */
    private static function getGhostly(): Ghostly
    {
        return Ghostly::getGhostly();
    }

    /**
     * @return Server
     */
    public static function getServerPM(): Server
    {
        return self::getGhostly()->getServer();
    }

    /**
     * @return PluginManager
     */
    public static function getPluginManager(): PluginManager
    {
        return self::getServerPM()->getPluginManager();
    }

    /**
     * @return TaskScheduler
     */
    public static function getTaskScheduler(): TaskScheduler
    {
        return self::getGhostly()->getScheduler();
    }

    /**
     * @return ResourcesManager
     */
    public static function getResourcesManager(): ResourcesManager
    {
        return new ResourcesManager();
    }

    /**
     * @return WorldManager
     */
    public static function getWorldManager(): WorldManager
    {
        return self::getServerPM()->getWorldManager();
    }

    /**
     * @return string
     */
    public static function getDataFolder(): string
    {
        return self::getGhostly()->getDataFolder();
    }
}