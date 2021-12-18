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

namespace zomarrd\ghostly\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerEntityInteractEvent;
use zomarrd\ghostly\modules\npc\EntityManager;
use zomarrd\ghostly\modules\npc\event\HumanEntityHitEvent;

final class EntityListener implements Listener
{
	/**
	 * @param HumanEntityHitEvent $event
	 *
	 * @return void
	 */
	public function onHitHumanEntity(HumanEntityHitEvent $event): void
	{
		$player = $event->getPlayer();
		$entity = $event->getEntity();

		if ($player->isOp()) {
			if ($player->isEditingAnEntity()) {
				EntityManager::showHumanEntityForm($entity, $player);
			}
		}
	}

	/**
	 * @param PlayerEntityInteractEvent $event
	 *
	 * @return void
	 */
	public function onInteractEntity(PlayerEntityInteractEvent $event): void
	{
		$entity = $event->getEntity();

		var_dump("aaa");
	}
}