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

use core\ghostly\task\TaskManager;
use pocketmine\network\mcpe\convert\SkinAdapterSingleton;
use pocketmine\plugin\{PluginBase, PluginLogger};
use ReflectionException;
use zomarrd\ghostly\events\EventsManager;
use zomarrd\ghostly\modules\invmenu\InvMenuHandler;
use zomarrd\ghostly\network\player\skin\SkinAdapter;

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

    protected function onLoad(): void
    {
        date_default_timezone_set("America/New_York");
        self::$logger = $this->getLogger();
        self::$ghostly = $this;

        GExtension::init();
    }

    /**
     * @throws ReflectionException
     */
    protected function onEnable(): void
    {
        $prefix = PREFIX;

        /* Mojang Skin Support*/
        SkinAdapterSingleton::set(new SkinAdapter());

        /* It is in charge of registering the plugin events. */
        new EventsManager();

        /* Administrator of all Task. */
        new TaskManager();

        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

        self::$logger->notice(PREFIX . "The Lobby system has been fully loaded!");
        self::$logger->notice("§c" . <<<INFO


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

    protected function onDisable(): void
    {
        GExtension::getServerManager()->getCurrentServer()->setOffline();
    }
}