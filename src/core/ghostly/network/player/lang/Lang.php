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

use core\ghostly\modules\form\SimpleForm;
use core\ghostly\network\GExtension;
use core\ghostly\network\player\GhostlyPlayer;
use core\ghostly\network\player\IPlayer;
use core\ghostly\network\utils\MySQLUtils;
use core\ghostly\network\utils\TextUtils;
use Exception;
use pocketmine\utils\Config;

/**
 * @todo: finalize this
 */
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
    public static array $users = [];

    /** @var Config[] */
    public static array $lang = [], $config;

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

    /**
     * This function applies the language to the player.
     */
    public function apply(): void
    {
        $pn = $this->getPlayerName();
        if (isset(GhostlyPlayer::$playerSettings[$pn])) {
            $data = GhostlyPlayer::$playerSettings[$pn];
            if ($data["language"] !== null && $data["language"] !== "null") {
                $this->set($data["language"], false);
            }
        }
    }

    /**
     * @return string Returns the language of the player.
     */
    public function get(): string
    {
        return self::$users[$this->getPlayerName()] ?? $this->getPlayer()->getLocale();
    }

    /**
     * @param string $id
     *
     * @return string Returns the string that contains the id.
     */
    public function getString(string $id): string
    {
        $str = self::$lang[$this->get()]->get("strings");
        return $str["$id"] ?? TextUtils::colorize($str["message.error"]);
    }

    public function showForm(string $type = "with.back.button"): void
    {
        $player = $this->getPlayer();
        $playerName = $this->getPlayerName();
        $form = new SimpleForm(function (GhostlyPlayer $player, $data){
           if (isset($data)) {

           }
        });

        $form->setTitle("");

        try {

        } catch (Exception $ex) {
            $player->sendMessage(PREFIX . "");
            if (GExtension::getServerPM()->isOp($playerName)) {
                $player->sendMessage("Error in line: {$ex->getLine()}, File: {$ex->getFile()} \n Error: {$ex->getMessage()}");
            }
        }
    }
}