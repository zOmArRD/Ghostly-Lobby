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

namespace core\ghostly\commands\npc;

use core\ghostly\commands\Command;
use core\ghostly\commands\ISubCommand;
use core\ghostly\network\player\lang\TranslationsKeys;
use core\ghostly\network\player\permission\DefaultPermissionNames;
use pocketmine\command\CommandSender;

final class NpcCmd extends Command
{
    /** @var ISubCommand[] */
    public static array $subCmd;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setPermissionMessage(TranslationsKeys::COMMAND_NOEXIST);
        $this->setPermission(DefaultPermissionNames::COMMAND_NPC);
        parent::__construct($name, "NPC Command", "/npc help");
        $this->registerSubCommands();
    }

    /**
     * It is in charge of registering the Subcommands
     *
     * @return void
     */
    public function registerSubCommands(): void
    {
        foreach (["help" => new NHelp(), "create" => new NCreate()] as $prefix => $subCmd) {
            self::$subCmd[$prefix] = $subCmd;
        }
    }

    /**
     * @return ISubCommand[]
     */
    public static function getSubCmd(): array
    {
        return self::$subCmd;
    }

    /**
     * @param CommandSender $sender
     * @param string        $commandLabel
     * @param array         $args
     *
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) {
            return;
        }

        if (!isset($args[0])) {
            self::$subCmd[$this->getSubcommand("help")]->executeSub($sender, []);
            return;
        }

        $prefix = $args[0];

        if ($this->getSubcommand($prefix) === null) {
            self::$subCmd[$this->getSubcommand("help")]->executeSub($sender, []);
            return;
        }

        array_shift($args);
        $subCmd = self::$subCmd[$this->getSubcommand($prefix)];
        $subCmd->executeSub($sender, $args);
    }

    /**
     * @param string $prefix
     *
     * @return string|null
     */
    public function getSubcommand(string $prefix): ?string
    {
        return match ($prefix) {
            "help" => "help",
            "create" => "create",
            default => null,
        };
    }
}