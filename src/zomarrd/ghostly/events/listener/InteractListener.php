<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 6/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\events\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemTransactionData;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\items\ItemsManager;
use zomarrd\ghostly\modules\npc\entity\HumanEntity;
use zomarrd\ghostly\modules\npc\event\HumanEntityHitEvent;
use zomarrd\ghostly\network\player\GhostlyPlayer;

final class InteractListener implements Listener
{
	private array $itemDelay;
	/** @var array */
	private array $hitEntity;

	/**
	 * @param PlayerInteractEvent $event
	 *
	 * @return void
	 */
	public function legacyInteract(PlayerInteractEvent $event): void
	{
		$player = $event->getPlayer();

		if (!GExtension::getServerPM()->isOp($player->getName())) $event->cancel();
	}

	/**
	 * @priority HIGHEST
	 */
	public function newInteract(DataPacketReceiveEvent $event): void
	{
		$player = $event->getOrigin()->getPlayer();
		$packet = $event->getPacket();

		switch (true) {
			case $packet instanceof InventoryTransactionPacket:
				if (!$player instanceof GhostlyPlayer) return;
				$trData = $packet->trData;
				if ($trData instanceof UseItemTransactionData) switch ($trData->getActionType()) {
					//case UseItemTransactionData::ACTION_CLICK_BLOCK:
					case UseItemTransactionData::ACTION_CLICK_AIR:
						//case UseItemTransactionData::ACTION_BREAK_BLOCK:
						$itemInHand = $player->getInventory()->getItemInHand();
						$cool_down = 1.5;

						if (!isset($this->itemDelay[$player->getName()]) or time() - $this->itemDelay[$player->getName()] >= $cool_down) {
							switch (true) {
								case $itemInHand->equals(ItemsManager::get('item.navigator')):
									$player->sendMessage('interact');
									break;
							}
							$this->itemDelay[$player->getName()] = time();
						}
						break;
				}
				break;
		}
		if (true == $packet instanceof InventoryTransactionPacket) {
			if (!$player instanceof GhostlyPlayer) return;
			$playerName = $player->getName();
			$trData = $packet->trData;

			if ($trData instanceof UseItemOnEntityTransactionData) switch ($trData->getActionType()) {
				case UseItemOnEntityTransactionData::ACTION_ATTACK:
				case UseItemOnEntityTransactionData::ACTION_INTERACT:
				case UseItemOnEntityTransactionData::ACTION_ITEM_INTERACT:
					$target = $player->getWorld()->getEntity($trData->getActorRuntimeId());
					if (!$target instanceof HumanEntity) return;
					$timeToNexHit = 2;

					if (!isset($this->hitEntity[$playerName]) or time() - $this->hitEntity[$playerName] >= $timeToNexHit) {

						$custom_event = new HumanEntityHitEvent($target, $player);
						$custom_event->call();

						$this->hitEntity[$player->getName()] = time();
					}
					break;
			}
		}
	}
}