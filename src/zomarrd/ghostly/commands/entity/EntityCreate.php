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

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\modules\npc\EntityManager;
use zomarrd\ghostly\modules\npc\Human;
use zomarrd\ghostly\network\player\GhostlyPlayer;
use zomarrd\ghostly\network\player\lang\TranslationsKeys;

final class EntityCreate extends BaseSubCommand
{

    /**
     * This is where all the arguments, permissions, sub-commands, etc would be registered
     */
    protected function prepare(): void
    {
        try {
            $this->registerArgument(0, new RawStringArgument("npcId", true));
            $this->registerArgument(1, new BooleanArgument("spawnToAll", true));
            $this->registerArgument(2, new RawStringArgument("nameTag", true));
        } catch (\Exception) {
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

        if (count($args) < 3) {
            $this->sendError(BaseCommand::ERR_INSUFFICIENT_ARGUMENTS);
            return;
        }

        $this->getHuman()->spawn($args["npcId"], $sender, (bool)$args["spawnToAll"], $args["nameTag"]);
    }


	/**
	 * @return Human
	 */
    private function getHuman(): Human
    {
        return EntityManager::getHuman();
    }
}