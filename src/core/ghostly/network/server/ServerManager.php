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

namespace core\ghostly\network\server;

use core\ghostly\GExtension;
use core\ghostly\Ghostly;
use core\ghostly\modules\mysql\AsyncQueue;
use core\ghostly\modules\mysql\query\RegisterServerQuery;
use core\ghostly\modules\mysql\query\SelectQuery;
use core\ghostly\modules\mysql\query\UpdateRowQuery;
use core\ghostly\network\resources\ResourcesManager;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;

final class ServerManager
{
    /** @var int */
    protected const REFRESH_TICKS = 60;

    /** @var Server[] */
    public static array $servers = [];

    /** @var Server */
    private static Server $currentServer;

    /**
     * @return Config
     */
    private function getConfig(): Config
    {
        return ResourcesManager::getFile("network.data.yml");
    }

    /**
     * It is the most important function.
     * <p></p>
     * It is in charge of registering the servers and verifying them.
     */
    public function load(): void
    {
        if ($this->getConfig()->get('current.server')['isEnabled'] !== "true") return;
        /** @var string $currentServerName */
        $currentServerName = $this->getConfig()->get('current.server')['name'];
        AsyncQueue::submitQuery(new RegisterServerQuery($currentServerName));
        Ghostly::$logger->info(PREFIX . "Registering the server in the database");
        sleep(1); // I DON'T KNOW REALlY BRO
        $this->reloadServers();
        GExtension::getTaskScheduler()->scheduleRepeatingTask(new ClosureTask(function () use ($currentServerName): void {
            $players = count(GExtension::getServerPM()->getOnlinePlayers());
            $isWhitelist = (GExtension::getServerPM()->hasWhitelist() ? 1 : 0);
            AsyncQueue::submitQuery(new UpdateRowQuery(["players" => "$players", "isWhitelisted" => "$isWhitelist"], "server", $currentServerName, "servers"));

            foreach (self::getServers() as $server) $server->sync();
        }), self::REFRESH_TICKS);
    }

    /**
     * Reloads the server array data from the database.
     * <br><br>
     * Useful for when more servers are added to the database.
     */
    public function reloadServers(): void
    {
        if ($this->getConfig()->get('current.server')['isEnabled'] !== "true") return;
        self::$servers = [];

        /** @var string $currentServerName */
        $currentServerName = self::getConfig()->get('current.server')['name'];
        AsyncQueue::submitQuery(new SelectQuery("SELECT * FROM servers"), function ($rows) use ($currentServerName) {
            foreach ($rows as $row) {
                $server = new Server($row["server"], (int)$row["players"], (bool)$row["isOnline"], (bool)$row["isWhitelisted"]);
                if ($row["server"] === $currentServerName) {
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
     * @return Server[]
     */
    public function getServers(): array
    {
        return self::$servers;
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

    /**
     * @param string $target
     *
     * @return Server|null
     */
    public static function getServer(string $target): ?Server
    {
        $servers = (new ServerManager)->getServers();

        foreach ($servers as $server) {
            if ($server->getName() === $target) return $server;
        }
        return null;
    }

    /**
     * @param string $server
     *
     * @return string
     */
    public static function getStatus(string $server): string
    {
        $server = self::getServer($server);
        return $server !== null ? $server->getStatus() : "";
    }
}