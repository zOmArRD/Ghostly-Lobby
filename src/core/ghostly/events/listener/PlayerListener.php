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

namespace core\ghostly\events\listener;

use core\ghostly\Ghostly;
use core\ghostly\network\player\GhostlyPlayer;
use pocketmine\block\Furnace;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

final class PlayerListener implements Listener
{
    public function __construct()
    {
        Ghostly::$logger->info("§a" . "Player Listener registered");
    }

    /**
     * @param PlayerCreationEvent $event
     *
     * @priority HIGH
     */
    public function onPlayerCreation(PlayerCreationEvent $event): void
    {
        $event->setPlayerClass(GhostlyPlayer::class);
    }

    /**
     * @param PlayerJoinEvent $event
     * @todo finalize this.
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        $event->setJoinMessage("");
        $player = $event->getPlayer();

        if (!$player instanceof GhostlyPlayer) return;
        $player->setLobbyItems();
    }

    public function onPlayerLeave(PlayerQuitEvent $event): void
    {
        $event->setQuitMessage("");
    }

    public function entityDamage(EntityDamageEvent $event): void
    {
        $event->cancel();
    }
}