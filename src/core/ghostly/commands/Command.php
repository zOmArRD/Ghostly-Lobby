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

abstract class Command extends \pocketmine\command\Command
{
    /** @var array */
    public static array $subCmd;

    public function registerSubCommands(): void{}

    public function getSubcommand(string $prefix): ?string
    {
        return null;
    }
}