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

namespace core\ghostly\network\player;


use core\ghostly\network\player\lang\Lang;
use core\ghostly\network\utils\TextUtils;
use Exception;
use pocketmine\player\Player;

final class GhostlyPlayer extends Player
{
    /** @var array */
    public static array $playerSettings = [];

    /** @var Lang */
    public Lang $langClass;

    public function setLangClass(): void
    {
        $this->langClass = new Lang($this);
    }

    public function getLangClass(): Lang
    {
        return $this->langClass;
    }

    /**
     * @param string $stringId
     *
     * @return string Returns the id of the translated message.
     */
    public function getTranslated(string $stringId): string
    {
        try {
            return TextUtils::colorize($this->getLangClass()->getString($stringId));
        } catch (Exception) {
            return "";
        }
    }

}