<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 11/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly;

use core\ghostly\modules\mysql\data\SQLStore;
use core\ghostly\modules\npc\EntityManager;
use core\ghostly\network\resources\ResourcesManager;
use core\ghostly\network\server\ServerManager;
use pocketmine\plugin\PluginManager;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server as PMServer;
use pocketmine\world\WorldManager;

final class GExtension
{
    /** @var ServerManager */
    private static ServerManager $serverManager;

    /** @var ResourcesManager */
    private static ResourcesManager $resourcesManager;

    /** @var EntityManager */
    private static EntityManager $entityManager;

    public static function init(): void
    {
        self::$resourcesManager = new ResourcesManager();
        self::$serverManager = new ServerManager();
        new SQLStore();

        self::$serverManager->init();
        self::$entityManager = new EntityManager();
    }

    /**
     * @return EntityManager
     */
    public static function getEntityManager(): EntityManager
    {
        return self::$entityManager;
    }

    public static function getResourcesManager(): ResourcesManager
    {
        return self::$resourcesManager;
    }

    public static function getServerManager(): ServerManager
    {
        return self::$serverManager;
    }

    public static function getPluginManager(): PluginManager
    {
        return self::getGhostly()->getServer()->getPluginManager();
    }

    private static function getGhostly(): Ghostly
    {
        return Ghostly::getGhostly();
    }

    public static function getTaskScheduler(): TaskScheduler
    {
        return self::getGhostly()->getScheduler();
    }

    public static function getWorldManager(): WorldManager
    {
        return self::getServerPM()->getWorldManager();
    }

    public static function getServerPM(): PMServer
    {
        return self::getGhostly()->getServer();
    }

    public static function getDataFolder(): string
    {
        return self::getGhostly()->getDataFolder();
    }
}