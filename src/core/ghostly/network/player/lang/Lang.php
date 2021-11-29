<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 25/11/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly\network\player\lang;

use core\ghostly\modules\mysql\AsyncQueue;
use core\ghostly\modules\mysql\query\UpdateRowQuery;
use core\ghostly\network\player\GhostlyPlayer;
use core\ghostly\network\player\IPlayer;
use core\ghostly\network\utils\MySQLUtils;
use pocketmine\utils\Config;


final class Lang implements IPlayer
{
    /** @var GhostlyPlayer */
    private GhostlyPlayer $player;

    public function setPlayer(GhostlyPlayer $player): void
    {
        $this->player = $player;
    }

    public function getPlayer(): GhostlyPlayer
    {
        return $this->player;
    }

    public function getPlayerName(): string
    {
        return $this->getPlayer()->getName();
    }

    public function __construct(GhostlyPlayer $player)
    {
        $this->setPlayer($player);
    }

    /** @var array */
    public static array $lang = [], $users = [];

    /** @var Config */
    public static Config $config;

    /**
     * @param string $language
     * @param bool   $safe
     */
    public function set(string $language, bool $safe): void
    {
        $pn = $this->getPlayerName();
        self::$users[$pn] = $language;
        if ($safe) {
            GhostlyPlayer::$playerSettings[$pn]["language"] = $language;
            MySQLUtils::UpdateRowQuery(["language" => "$language"], $pn, "settings");
        }
    }
}