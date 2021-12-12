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

namespace core\ghostly\commands;

use pocketmine\command\CommandSender;

interface ISubCommand
{
    /**
     * @param CommandSender $sender
     * @param array         $args
     *
     * @return void
     */
    public function onSubcommand(CommandSender $sender, array $args): void;
}