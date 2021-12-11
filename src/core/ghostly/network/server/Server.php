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

use core\ghostly\modules\mysql\AsyncQueue;
use core\ghostly\modules\mysql\query\SelectQuery;

class Server
{
    /** @var string */
    public string $name;

    /** @var int */
    public int $players;

    /** @var bool */
    public bool $isOnline, $isWhitelisted;

    /**
     * @param string $server
     * @param int    $players
     * @param bool   $isOnline
     * @param bool   $isWhitelisted
     */
    public function __construct(string $server = "Unknown", int $players = 0, bool $isOnline = false, bool $isWhitelisted = false)
    {
        $this->update($server, $players, $isOnline, $isWhitelisted);
    }

    /**
     * @param string $server
     * @param int    $players
     * @param bool   $isOnline
     * @param bool   $isWhitelisted
     */
    public function update(string $server = "Unknown", int $players = 0, bool $isOnline = false, bool $isWhitelisted = false): void
    {
        $this->setName($server);
        $this->setPlayers($players);
        $this->setIsOnline($isOnline);
        $this->setIsWhitelisted($isWhitelisted);
    }

    /**
     * @param bool $isWhitelisted
     */
    public function setIsWhitelisted(bool $isWhitelisted): void
    {
        $this->isWhitelisted = $isWhitelisted;
    }

    /**
     * Synchronize server data from database.
     *
     * @return void
     */
    public function sync(): void
    {
        AsyncQueue::runAsync(new SelectQuery("SELECT * FROM servers WHERE server='$this->name';"), function ($rows) {
            $row = $rows[0];
            if ($row !== null) {
                $this->setIsOnline((bool)$row["isOnline"]);
                $this->setPlayers((int)$row["players"]);
                $this->setIsWhitelisted((bool)$row["isWhitelisted"]);
            } else {
                $this->setIsOnline(false);
                $this->setPlayers(0);
                $this->setIsWhitelisted(false);
            }
        });
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isWhitelisted(): bool
    {
        return $this->isWhitelisted;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->isOnline() ? ("§a" . "PLAYING: §f" . $this->getPlayers()) : ("§c" . "OFFLINE");
    }

    /**
     * @return bool
     */
    public function isOnline(): bool
    {
        return $this->isOnline;
    }

    /**
     * @param bool $isOnline
     *
     * @return void
     */
    public function setIsOnline(bool $isOnline): void
    {
        $this->isOnline = $isOnline;
    }

    /**
     * @return int
     */
    public function getPlayers(): int
    {
        return $this->players;
    }

    /**
     * @param int $players
     *
     * @return void
     */
    public function setPlayers(int $players): void
    {
        $this->players = $players;
    }
}