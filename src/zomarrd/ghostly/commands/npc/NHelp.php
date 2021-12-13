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

final class NHelp implements ISubCommand
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
        $sender->sendMessage(PREFIX . "§bList of subcommands for npc system:");
        foreach (array_keys(NpcCmd::getSubCmd()) as $command) {
            $sender->sendMessage("§7- §a/npc $command");
        }
    }
}