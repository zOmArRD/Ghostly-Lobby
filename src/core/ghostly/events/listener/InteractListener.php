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
use core\ghostly\items\ItemsManager;
use core\ghostly\network\player\GhostlyPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemTransactionData;

class InteractListener implements Listener
{
    private array $itemDelay;

    public function __construct()
    {
        Ghostly::$logger->info("§b" . "InteractListener registered");
    }

    public function legacyInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();

        if (!GExtension::getServerPM()->isOp($player->getName())) $event->cancel();
    }

    public function newInteract(DataPacketReceiveEvent $event): void
    {
        $player = $event->getOrigin()->getPlayer();
        $pk = $event->getPacket();

        if (!$player instanceof GhostlyPlayer && !$pk instanceof InventoryTransactionPacket) return;

        try {
            $trData = $pk->trData;
        } catch (\Exception) {
            return;
        }

        if ($trData instanceof UseItemTransactionData) switch ($trData->getActionType()) {
            case UseItemTransactionData::ACTION_CLICK_BLOCK:
            case UseItemTransactionData::ACTION_CLICK_AIR:
            case UseItemTransactionData::ACTION_BREAK_BLOCK:
                $item = $player->getInventory()->getItemInHand();
                $cool_down = 1.5;

                if (!isset($this->itemDelay[$player->getName()]) or time() - $this->itemDelay[$player->getName()] >= $cool_down) {
                    switch (true) {
                        case $item->equals(ItemsManager::get("item.navigator")):
                            //FUNCTION HERE
                            break;
                    }
                    $this->itemDelay[$player->getName()] = time();
                }
                break;
        }
    }
}