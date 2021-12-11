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

namespace core\ghostly\network\resources;

use core\ghostly\GExtension;
use core\ghostly\Ghostly;
use core\ghostly\modules\mysql\AsyncQueue;
use core\ghostly\modules\mysql\query\InsertQuery;
use core\ghostly\modules\npc\EntityManager;
use core\ghostly\network\player\lang\Lang;
use core\ghostly\network\utils\TextUtils;
use pocketmine\utils\Config;
use pocketmine\world\World;

/**
 * @todo: finalize this
 */
final class ResourcesManager
{
    /** @var array|string[] */
    private array $listFiles = ['config.yml' => "1.0.0", 'scoreboard.yml' => "1.0.0", 'network.data.yml' => "1.0.0"];

    public function init(): void
    {
        Ghostly::$logger->notice("Resource management has started!");
        @mkdir(GExtension::getDataFolder());

        foreach ($this->listFiles as $file => $version) {
            self::getGhostly()->saveResource($file);

            $tFile = self::getFile($file);
            if ($tFile->get("version") !== $version) {
                Ghostly::$logger->error("The config.yml are not compatible with the plugin, the old config are in /resources/{$file}");
                rename(GExtension::getDataFolder() . $file, GExtension::getDataFolder() . $file . ".old");
                self::getGhostly()->saveResource($file);
                unset($tFile);
            }
        }

        $cFile = self::getFile("config.yml");

        define("PREFIX", TextUtils::colorize($cFile->get("prefix")));
        define("MySQL", $cFile->get("database"));
        define("SP", self::getFile("network.data.yml")->get("player.spawn"));

        Lang::$config = $cFile->get("language.available");
        unset($cFile);

        foreach (Lang::$config as $lang) {
            $iso = $lang["ISOCode"];
            self::getGhostly()->saveResource("lang/$iso.yml");
            Lang::$lang[$iso] = $this->getFile("lang/$iso.yml");
            Ghostly::$logger->info(PREFIX . "§a" . "The $iso language has been registered.");
        }

        if (SP['is.enabled'] === "true") {
            $levelName = SP['world']['name'];
            if (!GExtension::getWorldManager()->isWorldLoaded($levelName)) {
                GExtension::getWorldManager()->loadWorld($levelName);
                Ghostly::$logger->info(PREFIX . "§a" . "The world ($levelName) has been loaded.");
            }
            GExtension::getWorldManager()->getWorldByName($levelName)->setTime(World::TIME_NOON);
            GExtension::getWorldManager()->getWorldByName($levelName)->stopTime();
        }

        $this->checkTables();
        new EntityManager();

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
     * @param int    $type
     *
     * @return Config The config file
     */
    public static function getFile(string $file, int $type = Config::YAML): Config
    {
        return new Config(GExtension::getDataFolder() . "$file", $type);
    }

    private function checkTables(): void
    {
        Ghostly::$logger->info(PREFIX . "parsing the database tables");
        AsyncQueue::runAsync(new InsertQuery("CREATE TABLE IF NOT EXISTS servers(server TEXT, players INT DEFAULT 0, isOnline SMALLINT DEFAULT 0, isWhitelisted SMALLINT DEFAULT 0);"));
    }
}