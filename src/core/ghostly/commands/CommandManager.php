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
use pocketmine\command\Command as PMCommand;

final class CommandManager
{
    public function __construct()
    {
        $this->init();
    }

    /**
     * @param string    $prefix
     * @param PMCommand $command
     *
     * @return void
     */
    private function register(string $prefix, PMCommand $command): void
    {
        GExtension::getServerPM()->getCommandMap()->register($prefix, $command);
    }

    /**
     * @todo finish
     * @return void
     */
    public function init(): void
    {
        foreach (["npc" => new NpcCmd()] as $prefix => $command) {
            $this->register($prefix, $command);
        }
    }
}