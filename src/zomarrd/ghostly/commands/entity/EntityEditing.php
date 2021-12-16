<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 16/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\commands\entity;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use Exception;
use pocketmine\command\CommandSender;
use zomarrd\ghostly\network\player\GhostlyPlayer;
use zomarrd\ghostly\network\player\lang\TranslationsKeys;

final class EntityEditing extends BaseSubCommand
{

	/**
	 * This is where all the arguments, permissions, sub-commands, etc would be registered
	 */
	protected function prepare(): void
	{
		try {
			$this->registerArgument(0, new BooleanArgument("setEditing"));
		} catch (Exception) {
		}
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $aliasUsed
	 * @param array         $args
	 *
	 * @return void
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof GhostlyPlayer) {
			$sender->sendMessage(TranslationsKeys::ONLY_PLAYER);
			return;
		}

		if (count($args) < 0) {
			$this->sendError(BaseCommand::ERR_INSUFFICIENT_ARGUMENTS);
			return;
		}

		$sender->setIsEditingAnEntity($args["setEditing"]);
	}
}