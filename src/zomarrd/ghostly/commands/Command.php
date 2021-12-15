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

namespace zomarrd\ghostly\commands;

abstract class Command extends \pocketmine\command\Command
{
    public static array $subCmd;

    /**
     * @return void
     */
    public function registerSubCmd(): void
    {
    }

    /**
     * @param string $prefix
     *
     * @return string|null
     */
    public function getSubcommand(string $prefix): ?string
    {
        return null;
    }
}