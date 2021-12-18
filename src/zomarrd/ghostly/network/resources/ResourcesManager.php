<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 29/11/2021
 *
 * Copyright © 2021 GhostlyMC Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\network\resources;

use pocketmine\utils\Config;
use pocketmine\world\World;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\Ghostly;
use zomarrd\ghostly\network\player\lang\Lang;
use zomarrd\ghostly\network\utils\TextUtils;

/**
 * @todo: finalize this
 */
final class ResourcesManager
{
	/** @var array|string[] */
	private array $listFiles = ["config.yml" => "1.0.0", "scoreboard.yml" => "1.0.0", "network_config.json" => "1.0.0"];

	/** @var Config */
	private static Config $networkConfig, $scoreboardConfig;

	public function __construct()
	{
		$this->init();
	}

	public function init(): void
	{
		Ghostly::$logger->notice("Resource management has started!");
		@mkdir(self::getDataFolder());

		foreach ($this->listFiles as $file => $version) {
			self::getGhostly()->saveResource($file);

			$tFile = self::getFile($file);
			if ($tFile->get("version") !== $version) {
				Ghostly::$logger->error("The config.yml are not compatible with the plugin, the old config are in /resources/{$file}");
				rename(self::getDataFolder() . $file, self::getDataFolder() . $file . ".old");
				self::getGhostly()->saveResource($file);
			}
			unset($tFile);
		}

		self::$networkConfig = self::getFile("network_config.json");
		self::$scoreboardConfig = self::getFile("scoreboard.yml");

		$cFile = self::getFile("config.yml");

		define("PREFIX", TextUtils::colorize($cFile->get("prefix")));
		define("MySQL", $cFile->get("database"));
		define("SpawnOptions", self::getNetworkConfig()->get("player_spawn"));

		Lang::$config = $cFile->get("language.available");
		unset($cFile);

		foreach (Lang::$config as $lang) {
			$iso = $lang["ISOCode"];
			self::getGhostly()->saveResource("lang/$iso.yml");
			Lang::$lang[$iso] = self::getFile("lang/$iso.yml");
			Ghostly::$logger->info(PREFIX . "§a" . "The $iso language has been registered.");
		}

		if (SpawnOptions["is_enabled"] === true) {
			$levelName = SpawnOptions["world"]["name"];
			if (!GExtension::getWorldManager()->isWorldLoaded($levelName)) {
				GExtension::getWorldManager()->loadWorld($levelName);
				Ghostly::$logger->info(PREFIX . "§a" . "The world ($levelName) has been loaded.");
			}
			GExtension::getWorldManager()->getWorldByName($levelName)->setTime(World::TIME_NOON);
			GExtension::getWorldManager()->getWorldByName($levelName)->stopTime();
		}
		Ghostly::$logger->notice("Resource management has ended!");
	}

	/**
	 * @return Ghostly
	 */
	private static function getGhostly(): Ghostly
	{
		return Ghostly::getGhostly();
	}

	/**
	 * @param string $file
	 *
	 * @return Config
	 */
	public static function getFile(string $file): Config
	{
		return new Config(self::getDataFolder() . "$file");
	}

	/**
	 * @return string
	 */
	public static function getDataFolder(): string
	{
		return self::getGhostly()->getDataFolder();
	}

	/**
	 * @return Config
	 */
	public static function getNetworkConfig(): Config
	{
		return self::$networkConfig;
	}

	/**
	 * @return Config
	 */
	public static function getScoreboardConfig(): Config
	{
		return self::$scoreboardConfig;
	}
}