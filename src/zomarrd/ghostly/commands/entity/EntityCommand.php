<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 15/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\commands\entity;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use zomarrd\ghostly\lang\TranslationsKeys;
use zomarrd\ghostly\network\player\permission\PermissionNames;

final class EntityCommand extends BaseCommand
{
	/**
	 * @param Plugin $plugin
	 * @param string $name
	 */
	public function __construct(Plugin $plugin, string $name)
	{
		$this->setPermission(PermissionNames::COMMAND_NPC);
		$this->setPermissionMessage(TranslationsKeys::COMMAND_NOEXIST);
		parent::__construct($plugin, $name, 'Lobby entity manager', ['npc']);
	}

	/**
	 * @param CommandSender                   $sender
	 * @param string                          $aliasUsed
	 * @param array|array<string,mixed|array> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		$this->sendUsage();
	}

	/**
	 * This is where all the arguments, permissions, sub-commands, etc would be registered
	 */
	protected function prepare(): void
	{
		$this->registerSubCommand(
			new EntityCreate(
				'create',
				'Create an entity'
			)
		);

		$this->registerSubCommand(
			new EntityEditing(
				'setediting',
				'Switch to entity edit mode'
			)
		);

		$this->registerSubCommand(
			new EntityKill(
				'kill',
				'Delete entities, purge them all LOL',
				['purge']
			)
		);
	}
}