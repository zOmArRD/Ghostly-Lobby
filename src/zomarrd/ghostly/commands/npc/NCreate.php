<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 12/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\commands\npc;

use pocketmine\command\CommandSender;
use zomarrd\ghostly\commands\ISubCommand;
use zomarrd\ghostly\network\player\GhostlyPlayer;
use zomarrd\ghostly\network\player\lang\TranslationsKeys;

final class NCreate implements ISubCommand
{

    /**
     * @param CommandSender $sender
     * @param array         $args
     *
     * @return void
     * @todo finish
     */
    public function executeSub(CommandSender $sender, array $args): void
    {
        if (!$sender instanceof GhostlyPlayer) {
            $sender->sendMessage(TranslationsKeys::ONLY_PLAYER);
            return;
        }

        if (!isset($args[0])) {
            $sender->sendMessage(PREFIX . "§cUse: §7/npc create ");
            return;
        }


    }
}