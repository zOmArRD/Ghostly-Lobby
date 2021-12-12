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

namespace core\ghostly\network\server;

use core\ghostly\GExtension;
use core\ghostly\modules\mysql\AsyncQueue;
use core\ghostly\modules\mysql\query\SelectQuery;
use core\ghostly\modules\mysql\query\UpdateRowQuery;

class Server
{
    /** @var string */
    public string $name;

    /** @var int */
    public int $players;

    /** @var bool */
    public bool $is_online, $is_whitelisted, $is_maintenance;

    public function __construct(string $name, int $players = 0, bool $is_online = false, bool $is_maintenance = false, bool $is_whitelisted = false)
    {
        $this->setName($name);
        $this->setPlayers($players);
        $this->setIsOnline($is_online);
        $this->setIsMaintenance($is_maintenance);
        $this->setIsWhitelisted($is_whitelisted);
    }

    /**
     * @param bool $is_maintenance
     */
    public function setIsMaintenance(bool $is_maintenance): void
    {
        $this->is_maintenance = $is_maintenance;
    }

    /**
     * @param bool $is_whitelisted
     */
    public function setIsWhitelisted(bool $is_whitelisted): void
    {
        $this->is_whitelisted = $is_whitelisted;
    }

    /**
     * @return bool
     */
    public function isIsMaintenance(): bool
    {
        return $this->is_maintenance;
    }

    /**
     * @return bool
     */
    public function isIsWhitelisted(): bool
    {
        return $this->is_whitelisted;
    }

    /**
     * Synchronize server data from database.
     *
     * @return void
     */
    public function sync(): void
    {
        AsyncQueue::runAsync(new SelectQuery("SELECT * FROM network_servers WHERE server='$this->name';"), function ($rows) {
            $row = $rows[0];
            if ($row !== null) {
                $this->setIsOnline((bool)$row["is_online"]);
                $this->setPlayers((int)$row["players"]);
                $this->setIsWhitelisted((bool)$row["is_whitelisted"]);
                $this->setIsMaintenance((bool)$row["is_maintenance"]);
            } else {
                $this->setIsOnline(false);
                $this->setPlayers(0);
                $this->setIsWhitelisted(false);
                $this->setIsMaintenance(false);
            }
        });
    }

    /**
     * @todo Add if the server is under maintenance.
     * @return void
     */
    public function update(): void
    {
        $players = count(GExtension::getServerPM()->getOnlinePlayers());
        $isWhitelist = GExtension::getServerPM()->hasWhitelist() ? 1 : 0;
        AsyncQueue::runAsync(new UpdateRowQuery(["players" => $players, "is_whitelisted" => $isWhitelist], "server", $this->getName(), "network_servers"));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStatus(): string
    {
        return $this->isIsOnline() ? ("§a" . "PLAYING: §f" . $this->getPlayers()) : ("§c" . "OFFLINE");
    }

    public function isIsOnline(): bool
    {
        return $this->is_online;
    }

    public function setIsOnline(bool $is_online): void
    {
        $this->is_online = $is_online;
    }

    public function getPlayers(): int
    {
        return $this->players;
    }

    public function setPlayers(int $players): void
    {
        $this->players = $players;
    }

    public function setOffline(): void
    {
        AsyncQueue::runAsync(new UpdateRowQuery(["is_online" => 0, "players" => 0], "server", $this->getName(), "network_servers"));
    }
}