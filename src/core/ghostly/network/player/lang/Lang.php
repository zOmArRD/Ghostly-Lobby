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
            if ($data["language"] !== null && $data["language"] !== "null") $this->set($data["language"], false);
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

    public function showForm(int $type = 1): void
    {
        $player = $this->getPlayer();
        $playerName = $this->getPlayerName();
        $form = new SimpleForm(function (GhostlyPlayer $player, $data) {
            if (isset($data)) {
                if ($data == "back") {
                    /*todo: return to another form*/
                    return;
                } elseif ($data == "close") return;

                if ($this->get() !== $data) {
                    $this->set($data, true);
                    /*todo: update the inventory of the player*/

                    $player->sendMessage(PREFIX . TextUtils::colorize($this->getString("message.lang.set.done")));
                } else $player->sendMessage(PREFIX . TextUtils::colorize($this->getString("message.lang.set.fail")));
            }
        });

        $form->setTitle(TextUtils::colorize($this->getString("form.title.lang.selector")));

        try {
            foreach (Lang::$config as $lang) $form->addButton("§f" . $lang["name"], $lang["image.type"], $lang["image.link"], $lang["ISOCode"]);
        } catch (Exception $ex) {
            $player->sendMessage(PREFIX . "");
            if (GExtension::getServerPM()->isOp($playerName)) $player->sendMessage("Error in line: {$ex->getLine()}, File: {$ex->getFile()} \n Error: {$ex->getMessage()}");
        }

        switch ($type) {
            case 1:
                $form->addButton("form.button.back", $form::IMAGE_TYPE_PATH, "", "back");
                break;
            default:
                $form->addButton("form.button.close", $form::IMAGE_TYPE_PATH, "textures/gui/newgui/anvil-crossout", "close");
                break;
        }

        $player->sendForm($form);
    }
}