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

namespace zomarrd\ghostly\lang;

use Exception;
use pocketmine\utils\Config;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\modules\form\SimpleForm;
use zomarrd\ghostly\modules\mysql\AsyncQueue;
use zomarrd\ghostly\modules\mysql\query\UpdateRowQuery;
use zomarrd\ghostly\network\player\GhostlyPlayer;
use zomarrd\ghostly\network\player\IPlayer;
use zomarrd\ghostly\network\utils\TextUtils;

/**
 * DON'T USE THIS FOR NOW
 */
final class Lang extends IPlayer
{
    /** @var array */
    public static array $users = [];

    /** @var Config[] */
    public static array $lang = [], $config;

    /**
     * This function applies the language to the player.
     */
    public function apply(): void
    {
        $playerName = $this->getPlayerName();
        if (isset(GhostlyPlayer::$player_config[$playerName])) {
            $data = GhostlyPlayer::$player_config[$playerName];
            if ($data['language'] !== null && $data['language'] !== 'null') $this->set($data['language'], false);
        }
    }

    /**
     * @param string $language
     * @param bool   $safe
     */
    public function set(string $language, bool $safe): void
    {
        $pn = $this->getPlayerName();
        self::$users[$pn] = $language;
        if ($safe) {
            GhostlyPlayer::$player_config[$pn]['language'] = $language;
            AsyncQueue::runAsync(new UpdateRowQuery(['language' => "$language"], 'player_config', $pn, 'settings'));
        }
    }

    /**
     * @param int $type
     *
     * @return void
     */
    public function showForm(int $type = 1): void
    {
        $player = $this->getPlayer();
        $playerName = $this->getPlayerName();
        $form = new SimpleForm(function (GhostlyPlayer $player, $data) {
            if (isset($data)) {
                if ($data == 'back') {
                    /*todo: return to another form*/
                    return;
                } elseif ($data == 'close') return;

                if ($this->getLanguage() !== $data) {
                    $this->set($data, true);
                    /*todo: update the inventory of the player*/

                    $player->sendMessage(PREFIX . TextUtils::colorize($this->getString('message.lang.set.done')));
                } else $player->sendMessage(PREFIX . TextUtils::colorize($this->getString('message.lang.set.fail')));
            }
        });

        $form->setTitle(TextUtils::colorize($this->getString('form.title.lang.selector')));

        try {
            foreach (Lang::$config as $lang) $form->addButton('§f' . $lang['name'], $lang['image.type'], $lang['image.link'], $lang['ISOCode']);
        } catch (Exception $ex) {
            if (GExtension::getServerPM()->isOp($playerName)) $player->sendMessage("Error in line: {$ex->getLine()}, File: {$ex->getFile()} \n Error: {$ex->getMessage()}");
        }

        switch ($type) {
            case 1:
                $form->addButton('form.button.back', $form::IMAGE_TYPE_PATH, '', 'back');
                break;
            default:
                $form->addButton('form.button.close', $form::IMAGE_TYPE_PATH, 'textures/gui/newgui/anvil-crossout', 'close');
                break;
        }

        $player->sendForm($form);
    }

    /**
     * @return string Returns the language of the player.
     */
    public function getLanguage(): string
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
        $str = self::$lang[$this->getLanguage()]->get('strings');
        return $str["$id"] ?? TextUtils::colorize($str['message.error']);
    }
}