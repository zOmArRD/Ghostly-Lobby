<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 6/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\events\listener;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\event\Listener;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\Ghostly;

final class WorldListener implements Listener
{
    /**
     * @param LeavesDecayEvent $event
     *
     * @return void
     */
    public function leavesDecayEvent(LeavesDecayEvent $event): void
    {
        $event->cancel();
    }

    /**
     * @param BlockBreakEvent $event
     *
     * @return void
     */
    public function blockBreakEvent(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();

        $levelName = SpawnOptions['is.enabled'] == 'true' ? SpawnOptions['world']['name'] : $player->getWorld()->getFolderName();

        if ($player->getWorld()->getFolderName() === $levelName) if (!GExtension::getServerPM()->isOp($player->getName())) $event->cancel();
    }

    /**
     * @param BlockPlaceEvent $event
     *
     * @return void
     */
    public function blockPlaceEvent(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();

        $levelName = SpawnOptions['is.enabled'] == 'true' ? SpawnOptions['world']['name'] : $player->getWorld()->getFolderName();

        if ($player->getWorld()->getFolderName() === $levelName) if (!GExtension::getServerPM()->isOp($player->getName())) $event->cancel();
    }
}