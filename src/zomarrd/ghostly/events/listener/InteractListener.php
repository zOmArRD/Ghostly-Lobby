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

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\JwtException;
use pocketmine\network\mcpe\JwtUtils;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemTransactionData;
use pocketmine\network\mcpe\protocol\types\login\ClientData;
use pocketmine\network\mcpe\raklib\RakLibInterface;
use pocketmine\network\PacketHandlingException;
use ReflectionClass;
use ReflectionException;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\Ghostly;
use zomarrd\ghostly\items\ItemsManager;
use zomarrd\ghostly\network\player\GhostlyPlayer;

class InteractListener implements Listener
{
    private array $itemDelay;

    public function __construct()
    {
        Ghostly::$logger->info('§b' . 'InteractListener registered');
    }

    /**
     * @param PlayerInteractEvent $event
     *
     * @return void
     */
    public function legacyInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();

        if (!GExtension::getServerPM()->isOp($player->getName())) $event->cancel();
    }

    /**
     * @priority HIGHEST
     */
    public function newInteract(DataPacketReceiveEvent $event): void
    {
        $player = $event->getOrigin()->getPlayer();
        $packet = $event->getPacket();

        switch (true) {
            case $packet instanceof LoginPacket:
                $clientData = $this->parseClientData($packet->clientDataJwt);

                foreach (GExtension::getServerPM()->getNetwork()->getInterfaces() as $interface) {
                    if ($interface instanceof RakLibInterface) {
                        $class = new ReflectionClass($interface);
                        var_dump($class->getProperties());
                    }
                }
                /*$property = $class->getProperty("ip");
                $property->setAccessible(true);
                $property->setValue($class, $clientData->PlatformOfflineId);*/
                break;
            case $packet instanceof InventoryTransactionPacket:
                if (!$player instanceof GhostlyPlayer) return;
                $trData = $packet->trData;
                if ($trData instanceof UseItemTransactionData) switch ($trData->getActionType()) {
                    case UseItemTransactionData::ACTION_CLICK_BLOCK:
                    case UseItemTransactionData::ACTION_CLICK_AIR:
                    case UseItemTransactionData::ACTION_BREAK_BLOCK:
                        $itemInHand = $player->getInventory()->getItemInHand();
                        $cool_down = 1.5;

                        if (!isset($this->itemDelay[$player->getName()]) or time() - $this->itemDelay[$player->getName()] >= $cool_down) {
                            switch (true) {
                                case $itemInHand->equals(ItemsManager::get('item.navigator')):
                                    $player->sendMessage('interact');
                                    break;
                            }
                            $this->itemDelay[$player->getName()] = time();
                        }
                        break;
                }
                break;
        }
    }

    /**
     * @param string $clientDataJwt
     *
     * @return ClientData
     */
    protected function parseClientData(string $clientDataJwt): ClientData
    {
        try {
            [, $clientDataClaims,] = JwtUtils::parse($clientDataJwt);
        } catch (JwtException $e) {
            throw PacketHandlingException::wrap($e);
        }

        $mapper = new \JsonMapper();
        $mapper->bEnforceMapType = false; //TODO: we don't really need this as an array, but right now we don't have enough models
        $mapper->bExceptionOnMissingData = true;
        $mapper->bExceptionOnUndefinedProperty = true;
        try {
            $clientData = $mapper->map($clientDataClaims, new ClientData());
        } catch (\JsonMapper_Exception $e) {
            throw PacketHandlingException::wrap($e);
        }
        return $clientData;
    }
}