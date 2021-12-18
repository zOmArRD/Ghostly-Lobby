<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 18/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);


namespace zomarrd\ghostly\commands;

use pocketmine\command\SimpleCommandMap;
use zomarrd\ghostly\commands\entity\EntityCommand;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\Ghostly;

final class CommandManager
{
	/**
	 * @param Ghostly $ghostly
	 */
	public function __construct(Ghostly $ghostly)
	{
		$this->getCommandMap()->registerAll('bukkit', [
			new EntityCommand($ghostly, 'entity')
		]);
	}

	/**
	 * @return SimpleCommandMap
	 */
	private function getCommandMap(): SimpleCommandMap
	{
		return GExtension::getServerPM()->getCommandMap();
	}

}