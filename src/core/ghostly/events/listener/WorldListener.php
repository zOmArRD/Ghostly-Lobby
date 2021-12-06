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

namespace core\ghostly\events\listener;

use core\ghostly\GExtension;
use core\ghostly\Ghostly;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\event\Listener;

class WorldListener implements Listener
{
    public function __construct()
    {
        Ghostly::$logger->info("§b" . "WordListener registered");
    }

    public function leavesDE(LeavesDecayEvent $event): void
    {
        $event->cancel();
    }

    public function blockBreak(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();

        $lvn = SP['is.enabled'] == "true" ? SP['world']['name'] : $player->getWorld()->getFolderName();

        if ($player->getWorld()->getFolderName() === $lvn) if (!GExtension::getServerPM()->isOp($player->getName())) $event->cancel();
    }

    public function blockPlace(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();

        $lvn = SP['is.enabled'] == "true" ? SP['world']['name'] : $player->getWorld()->getFolderName();

        if ($player->getWorld()->getFolderName() === $lvn) if (!GExtension::getServerPM()->isOp($player->getName())) $event->cancel();
    }
}