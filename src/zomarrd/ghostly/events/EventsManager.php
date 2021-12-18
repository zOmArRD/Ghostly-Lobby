<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 2/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\events;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginManager;
use zomarrd\ghostly\events\listener\EntityListener;
use zomarrd\ghostly\events\listener\InteractListener;
use zomarrd\ghostly\events\listener\PlayerListener;
use zomarrd\ghostly\events\listener\WorldListener;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\Ghostly;

final class EventsManager
{
	public function __construct()
	{
		$this->registerAll([
			new PlayerListener(),
			new WorldListener(),
			new InteractListener(),
			new EntityListener()
		]);
	}

	/**
	 * @param array $events
	 *
	 * @return void
	 */
	private function registerAll(array $events): void
	{
		foreach ($events as $event) {
			$this->register($event);
		}
	}

	/**
	 * @param Listener $event
	 */
	private function register(Listener $event): void
	{
		$this->getPluginManager()->registerEvents($event, Ghostly::getGhostly());
	}

	/**
	 * @return PluginManager
	 */
	private function getPluginManager(): PluginManager
	{
		return GExtension::getPluginManager();
	}
}