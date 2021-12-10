<?php

declare(strict_types=1);

namespace core\ghostly\modules\invmenu;

use core\ghostly\modules\invmenu\session\PlayerManager;
use core\ghostly\modules\invmenu\type\InvMenuTypeRegistry;
use InvalidArgumentException;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use ReflectionException;

final class InvMenuHandler
{

    private static ?Plugin $registrant = null;
    private static InvMenuTypeRegistry $type_registry;
    private static PlayerManager $player_manager;

    /**
     * @throws ReflectionException
     */
    public static function register(Plugin $plugin): void
    {
        if (self::isRegistered()) throw new InvalidArgumentException("{$plugin->getName()} attempted to register " . self::class . " twice.");

        self::$registrant = $plugin;
        self::$type_registry = new InvMenuTypeRegistry();
        self::$player_manager = new PlayerManager(self::getRegistrant());
        Server::getInstance()->getPluginManager()->registerEvents(new InvMenuEventHandler(self::getPlayerManager()), $plugin);
    }

    public static function isRegistered(): bool
    {
        return self::$registrant instanceof Plugin;
    }

    public static function getRegistrant(): Plugin
    {
        return self::$registrant;
    }

    public static function getPlayerManager(): PlayerManager
    {
        return self::$player_manager;
    }

    public static function getTypeRegistry(): InvMenuTypeRegistry
    {
        return self::$type_registry;
    }
}