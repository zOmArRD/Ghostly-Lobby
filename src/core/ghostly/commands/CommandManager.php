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

use core\ghostly\commands\npc\NpcCmd;
use core\ghostly\GExtension;
use pocketmine\command\SimpleCommandMap;

final class CommandManager
{
    public function __construct()
    {
        $this->setDefaultsCommands();
    }

    private function setDefaultsCommands(): void
    {
        $this->getCommandMap()->
        registerAll("bukkit", [
            new NpcCmd("npc")
        ]);
    }

    private function getCommandMap(): SimpleCommandMap
    {
        return GExtension::getServerPM()->getCommandMap();
    }
}