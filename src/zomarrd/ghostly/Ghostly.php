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

namespace zomarrd\ghostly;

use CortexPE\Commando\exception\HookAlreadyRegistered;
use CortexPE\Commando\PacketHooker;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\convert\SkinAdapterSingleton;
use pocketmine\plugin\{PluginBase, PluginLogger};
use zomarrd\ghostly\commands\CommandManager;
use zomarrd\ghostly\commands\entity\EntityCommand;
use zomarrd\ghostly\events\EventsManager;
use zomarrd\ghostly\listener\EntityListener;
use zomarrd\ghostly\listener\InteractListener;
use zomarrd\ghostly\listener\PlayerListener;
use zomarrd\ghostly\listener\WorldListener;
use zomarrd\ghostly\network\player\skin\SkinAdapter;
use zomarrd\ghostly\task\TaskManager;

final class Ghostly extends PluginBase
{
	/** @var Ghostly */
	public static Ghostly $ghostly;

	/** @var PluginLogger */
	public static PluginLogger $logger;

	/**
	 * @return Ghostly
	 */
	public static function getGhostly(): Ghostly
	{
		return self::$ghostly;
	}

	/**
	 * Called when the plugin is loaded, before calling onEnable()
	 */
	protected function onLoad(): void
	{
		date_default_timezone_set('America/New_York');
		self::$logger = $this->getLogger();
		self::$ghostly = $this;

		GExtension::init();
		parent::onLoad();
	}

	/**
	 * Called when the plugin is enabled
	 *
	 * @throws HookAlreadyRegistered
	 */
	protected function onEnable(): void
	{
		$prefix = PREFIX;

		/* Mojang Skin Support*/
		SkinAdapterSingleton::set(new SkinAdapter());

		/* It is in charge of registering the plugin events. */
		$this->loadListener([
			new EntityListener(),
			new InteractListener(),
			new PlayerListener(),
			new WorldListener()
		]);

		/* Administrator of all Task. */
		new TaskManager();

		if (!PacketHooker::isRegistered()) {
			PacketHooker::register($this);
		}

		$this->getServer()->getCommandMap()->registerAll('bukkit', [
			new EntityCommand($this, 'entity')
		]);

		self::$logger->notice(PREFIX . 'The Lobby system has been fully loaded!');
		self::$logger->notice('§c' . <<<INFO


         $$$$$$\  $$\                             $$\     $$\           $$\      $$\  $$$$$$\  
        $$  __$$\ $$ |                            $$ |    $$ |          $$$\    $$$ |$$  __$$\ 
        $$ /  \__|$$$$$$$\   $$$$$$\   $$$$$$$\ $$$$$$\   $$ |$$\   $$\ $$$$\  $$$$ |$$ /  \__|
        $$ |$$$$\ $$  __$$\ $$  __$$\ $$  _____|\_$$  _|  $$ |$$ |  $$ |$$\$$\$$ $$ |$$ |      
        $$ |\_$$ |$$ |  $$ |$$ /  $$ |\$$$$$$\    $$ |    $$ |$$ |  $$ |$$ \$$$  $$ |$$ |      
        $$ |  $$ |$$ |  $$ |$$ |  $$ | \____$$\   $$ |$$\ $$ |$$ |  $$ |$$ |\$  /$$ |$$ |  $$\ 
        \$$$$$$  |$$ |  $$ |\$$$$$$  |$$$$$$$  |  \$$$$  |$$ |\$$$$$$$ |$$ | \_/ $$ |\$$$$$$  |
         \______/ \__|  \__| \______/ \_______/    \____/ \__| \____$$ |\__|     \__| \______/ 
                                                              $$\   $$ |                       
                                                              \$$$$$$  |                       
                                                               \______/         
                                                               
         $prefix §fCreated by zOmArRD :)                                                                     
INFO
		);
	}

	/**
	 * @param array $listeners
	 *
	 * @return void
	 */
	private function loadListener(array $listeners)
	{
		$manager = $this->getServer()->getPluginManager();
		foreach ($listeners as $listener) {
			$manager->registerEvents($listener, $this);
		}
	}

	/**
	 * Called when the plugin is disabled
	 * Use this to free open things and finish actions
	 */
	protected function onDisable(): void
	{
		GExtension::getServerManager()->getCurrentServer()?->setOnline(false);
	}
}