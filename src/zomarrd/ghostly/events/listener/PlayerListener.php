<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 5/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\events\listener;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use zomarrd\ghostly\network\player\GhostlyPlayer;

final class PlayerListener implements Listener
{
	/**
	 * @param PlayerCreationEvent $event
	 *
	 * @priority HIGH
	 */
	public function onPlayerCreation(PlayerCreationEvent $event): void
	{
		$event->setPlayerClass(GhostlyPlayer::class);
	}

	/**
	 * @param PlayerJoinEvent $event
	 *
	 * @todo finalize this.
	 */
	public function onPlayerJoin(PlayerJoinEvent $event): void
	{
		$event->setJoinMessage('');
		$player = $event->getPlayer();

		if (!$player instanceof GhostlyPlayer) return;
		$player->setLobbyItems();
	}

	/**
	 * @param PlayerQuitEvent $event
	 *
	 * @return void
	 */
	public function onPlayerLeave(PlayerQuitEvent $event): void
	{
		$event->setQuitMessage('');
	}

	/**
	 * @param EntityDamageEvent $event
	 *
	 * @return void
	 */
	public function entityDamage(EntityDamageEvent $event): void
	{
		$event->cancel();
	}
}