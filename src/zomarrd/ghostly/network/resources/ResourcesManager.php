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
    private array $listFiles = ['config.yml' => '1.0.0', 'scoreboard.yml' => '1.0.0', 'network.data.yml' => '1.0.0'];

    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        Ghostly::$logger->notice('Resource management has started!');
        @mkdir(GExtension::getDataFolder());

        foreach ($this->listFiles as $file => $version) {
            self::getGhostly()->saveResource($file);

            $tFile = self::getFile($file);
            if ($tFile->get('version') !== $version) {
                Ghostly::$logger->error("The config.yml are not compatible with the plugin, the old config are in /resources/{$file}");
                rename(GExtension::getDataFolder() . $file, GExtension::getDataFolder() . $file . '.old');
                self::getGhostly()->saveResource($file);
                unset($tFile);
            }
        }

        $cFile = self::getFile('config.yml');

        define('PREFIX', TextUtils::colorize($cFile->get('prefix')));
        define('MySQL', $cFile->get('database'));
        define('SpawnOptions', self::getFile('network.data.yml')->get('player.spawn'));

        Lang::$config = $cFile->get('language.available');
        unset($cFile);

        foreach (Lang::$config as $lang) {
            $iso = $lang['ISOCode'];
            self::getGhostly()->saveResource("lang/$iso.yml");
            Lang::$lang[$iso] = $this->getFile("lang/$iso.yml");
            Ghostly::$logger->info(PREFIX . '§a' . "The $iso language has been registered.");
        }

        if (SpawnOptions['is.enabled'] === 'true') {
            $levelName = SpawnOptions['world']['name'];
            if (!GExtension::getWorldManager()->isWorldLoaded($levelName)) {
                GExtension::getWorldManager()->loadWorld($levelName);
                Ghostly::$logger->info(PREFIX . '§a' . "The world ($levelName) has been loaded.");
            }
            GExtension::getWorldManager()->getWorldByName($levelName)->setTime(World::TIME_NOON);
            GExtension::getWorldManager()->getWorldByName($levelName)->stopTime();
        }
        Ghostly::$logger->notice('Resource management has ended!');
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
}