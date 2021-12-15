<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 20/7/2021
 *
 * Copyright © 2021 Greek Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\network\server;

use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\Ghostly;
use zomarrd\ghostly\modules\mysql\AsyncQueue;
use zomarrd\ghostly\modules\mysql\query\RegisterServerQuery;
use zomarrd\ghostly\modules\mysql\query\SelectQuery;
use zomarrd\ghostly\network\resources\ResourcesManager;

final class ServerManager
{
    /** @var int */
    protected const REFRESH_TICKS = 60;

    /** @var Server[] */
    public static array $servers = [];

    /** @var Server */
    private static Server $currentServer;

    /**
     * @param string $server
     *
     * @return string
     */
    public static function getStatus(string $server): string
    {
        $server = self::getServer($server);
        return $server !== null ? $server->getStatus() : '';
    }

    /**
     * @param string $target
     *
     * @return Server|null
     */
    public static function getServer(string $target): ?Server
    {
        $servers = GExtension::getServerManager()->getServers();

        foreach ($servers as $server) {
            if ($server->getName() === $target) return $server;
        }
        return null;
    }

    /**
     * @return Server[]
     */
    public function getServers(): array
    {
        return self::$servers;
    }

    /**
     * It is the most important function.
     * <p></p>
     * It is in charge of registering the servers and verifying them.
     *
     * @return void
     */
    public function init(): void
    {
        if ($this->getConfig()->get('current.server')['is.enabled'] !== 'true') return;
        /** @var string $currentServerName */
        $currentServerName = $this->getConfig()->get('current.server')['name'];
        AsyncQueue::runAsync(new RegisterServerQuery($currentServerName));
        Ghostly::$logger->info('Registering the server in the database');
        sleep(1); // IDK
        $this->reloadServers();
        GExtension::getTaskScheduler()->scheduleDelayedRepeatingTask(new ClosureTask(function () use ($currentServerName): void {
            GExtension::getServerManager()->getCurrentServer()->update();
            foreach (self::getServers() as $server) $server->sync();
        }), 40, self::REFRESH_TICKS);
    }

    /**
     * @return Config
     */
    private function getConfig(): Config
    {
        return ResourcesManager::getFile('network.data.yml');
    }

    /**
     * Reloads the server array data from the database.
     * <br><br>
     * Useful for when more servers are added to the database.
     *
     * @return void
     */
    public function reloadServers(): void
    {
        if ($this->getConfig()->get('current.server')['is.enabled'] !== 'true') return;

        self::$servers = [];

        /** @var string $currentServerName */
        $currentServerName = self::getConfig()->get('current.server')['name'];
        AsyncQueue::runAsync(new SelectQuery('SELECT * FROM network_servers'), function ($rows) use ($currentServerName) {

            foreach ($rows as $row) {
                $server = new Server($row['server'], (int)$row['players'], (bool)$row['is_online'], (bool)$row['is_maintenance'], (bool)$row['is_whitelisted']);
                if ($row['server'] === $currentServerName) {
                    self::$currentServer = $server;
                    Ghostly::$logger->info(PREFIX . "The server ($currentServerName) has been registered in the database.");
                } else {
                    self::$servers[] = $server;
                    Ghostly::$logger->notice(PREFIX . "A new server has been registered | ($server->name)");
                }
            }
        });
    }

    /**
     * @return Server
     */
    public function getCurrentServer(): Server
    {
        return self::$currentServer;
    }

    /**
     * @param string $name
     *
     * @return Server|null
     */
    public function getServerByName(string $name): ?Server
    {
        foreach (self::getServers() as $server) {
            return ($server->getName() === $name) ? $server : null;
        }
        return null;
    }

    /**
     * Get all the players that are in the network.
     *
     * @return int
     */
    public function getNetworkPlayers(): int
    {
        $players = 0;
        foreach (self::getServers() as $server) $players += $server->getPlayers();

        $players += count(GExtension::getServerPM()->getOnlinePlayers());

        return $players;
    }
}