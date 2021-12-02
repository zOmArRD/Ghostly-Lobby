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

use core\ghostly\Ghostly;
use core\ghostly\network\player\lang\Lang;
use core\ghostly\network\utils\TextUtils;
use pocketmine\utils\Config;

/**
 * @todo: finalize this
 */
final class ResourcesManager
{
    /** @var array|string[] */
    private array $listFiles = ['config.yml', 'scoreboard.yml', 'network.data.yml'];

    /**
     * @param string $file
     * @param int    $type
     *
     * @return Config The config file
     */
    public static function getFile(string $file, int $type = Config::YAML): Config
    {
        return new Config(Ghostly::getGhostly()->getDataFolder() . "$file", $type);
    }

    public function init(): void
    {
        Ghostly::$logger->info("Resource management has started!");
        @mkdir(Ghostly::getGhostly()->getDataFolder());

        foreach ($this->listFiles as $file) {
            self::getGhostly()->saveResource($file);
        }

        $cFile = self::getFile("config.yml");

        define("PREFIX", TextUtils::colorize($cFile->get("prefix")));
        define("MySQL", $cFile->get("database"));

        Lang::$config = $cFile->get("language.available");

        foreach (Lang::$config as $lang) {
            $iso = $lang["ISOCode"];
            self::getGhostly()->saveResource("lang/$iso.yml");
            Lang::$lang[$iso] = $this->getFile("lang/$iso.yml");
            Ghostly::$logger->info(PREFIX . "§a" . "The $iso language has been registered.");
        }
    }

    /**
     * @return Ghostly
     */
    private static function getGhostly(): Ghostly
    {
        return Ghostly::getGhostly();
    }
}