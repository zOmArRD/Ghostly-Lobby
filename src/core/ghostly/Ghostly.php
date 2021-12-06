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

namespace core\ghostly;

use core\ghostly\events\EventsManager;
use core\ghostly\network\player\skin\SkinAdapter;
use core\ghostly\task\TaskManager;
use pocketmine\network\mcpe\convert\SkinAdapterSingleton;
use pocketmine\plugin\{PluginBase, PluginLogger};

final class Ghostly extends PluginBase
{
    /** @var Ghostly */
    public static Ghostly $ghostly;

    /** @var PluginLogger  */
    public static PluginLogger $logger;

    /** @var string|null */
    public ?string $prefix;

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

        GExtension::getResourcesManager()->init();
    }

    protected function onEnable(): void
    {
        $this->prefix = PREFIX;

        /* Mojang Skin Support*/
        SkinAdapterSingleton::set(new SkinAdapter());

        /* It is in charge of registering the plugin events. */
        new EventsManager();

        /* Administrator of all Task. */
        new TaskManager();

        self::$logger->notice(PREFIX . "The core has been fully loaded!");
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
                                                               
         $this->prefix §fCreated by zOmArRD :)                                                                     
INFO
        );
    }
}