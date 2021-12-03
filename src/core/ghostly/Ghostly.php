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

use core\ghostly\events\EventsManager;
use core\ghostly\modules\mysql\AsyncQueue;
use core\ghostly\modules\mysql\query\InsertQuery;
use core\ghostly\modules\mysql\query\UpdateRowQuery;
use core\ghostly\network\player\skin\SkinAdapter;
use core\ghostly\task\TaskManager;
use pocketmine\network\mcpe\convert\SkinAdapterSingleton;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLogger;

final class Ghostly extends PluginBase
{
    /** @var Ghostly */
    public static Ghostly $ghostly;

    /** @var PluginLogger */
    public static PluginLogger $logger;

    protected function onLoad(): void
    {
        self::$ghostly = $this;
        self::$logger = $this->getLogger();
        self::$logger->info("§a" ."The core is being loaded!");

        GExtension::getResourcesManager()->init();
        $this->MySQLScan();
        GExtension::getServerManager()->load();
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

    protected function onDisable(): void
    {
        //MySQLUtils::UpdateRowQuery(["isOnline" => 0, "players" => 0], "server", GExtension::getServerManager()->getCurrentServer()->getName(), "servers");
        AsyncQueue::submitQuery(new UpdateRowQuery(["isOnline" => 0, "players" => 0], "server",GExtension::getServerManager()->getCurrentServer()->getName(), "servers"));
    }

    /**
     * @return Ghostly
     */
    public static function getGhostly(): Ghostly
    {
        return self::$ghostly;
    }

    /**
     * @todo add the necessary tables.
     */
    public function MySQLScan(): void
    {
        self::$logger->info(PREFIX . "MySQL scan in progress...");
        AsyncQueue::submitQuery(new InsertQuery("CREATE TABLE IF NOT EXISTS servers(server TEXT, players INT DEFAULT 0, isOnline SMALLINT DEFAULT 0, isWhitelisted SMALLINT DEFAULT  0);"));
        AsyncQueue::submitQuery(new InsertQuery("CREATE TABLE IF NOT EXISTS settings(player TEXT, lang TEXT, scoreboard SMALLINT DEFAULT 1);"));
        self::$logger->info(PREFIX . "MySQL scan finished!");
    }
}