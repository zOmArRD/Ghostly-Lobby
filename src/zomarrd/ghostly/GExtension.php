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

namespace zomarrd\ghostly;

use zomarrd\ghostly\network\server\ServerManager;
use pocketmine\plugin\PluginManager;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server as PMServer;
use pocketmine\world\WorldManager;
use zomarrd\ghostly\commands\CommandManager;
use zomarrd\ghostly\modules\mysql\data\SQLStore;
use zomarrd\ghostly\modules\npc\EntityManager;
use zomarrd\ghostly\network\resources\ResourcesManager;

final class GExtension
{
    /** @var ServerManager */
    private static ServerManager $serverManager;

    /** @var ResourcesManager */
    private static ResourcesManager $resourcesManager;

    /** @var EntityManager */
    private static EntityManager $entityManager;

    /**
     * @return void
     */
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

    /**
     * @return ResourcesManager
     */
    public static function getResourcesManager(): ResourcesManager
    {
        return self::$resourcesManager;
    }

    /**
     * @return ServerManager
     */
    public static function getServerManager(): ServerManager
    {
        return self::$serverManager;
    }

    /**
     * @return PluginManager
     */
    public static function getPluginManager(): PluginManager
    {
        return self::getGhostly()->getServer()->getPluginManager();
    }

    /**
     * @return Ghostly
     */
    private static function getGhostly(): Ghostly
    {
        return Ghostly::getGhostly();
    }

    /**
     * @return TaskScheduler
     */
    public static function getTaskScheduler(): TaskScheduler
    {
        return self::getGhostly()->getScheduler();
    }

    /**
     * @return WorldManager
     */
    public static function getWorldManager(): WorldManager
    {
        return self::getServerPM()->getWorldManager();
    }

    /**
     * @return PMServer
     */
    public static function getServerPM(): PMServer
    {
        return self::getGhostly()->getServer();
    }

    /**
     * @return string
     */
    public static function getDataFolder(): string
    {
        return self::getGhostly()->getDataFolder();
    }
}