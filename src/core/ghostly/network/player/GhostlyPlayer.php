<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 25/11/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly\network\player;

use core\ghostly\Ghostly;
use core\ghostly\items\ItemsManager;
use core\ghostly\modules\scoreboard\Scoreboard;
use Exception;
use pocketmine\item\Item;
use pocketmine\player\Player;

final class GhostlyPlayer extends Player
{

    /** @var bool */
    private bool $loaded = false;

    /** @var Scoreboard */
    private Scoreboard $scoreboardSession;

    public function getScoreboardSession(): Scoreboard
    {
        return $this->scoreboardSession;
    }

    /**
     * Very important function in the core structure
     */
    public function initGhostlyPlayer(): void
    {
        new ItemsManager($this);
        $this->scoreboardSession = new Scoreboard($this);

        $this->loaded = true;
    }

    public function onUpdate(int $currentTick): bool
    {
        if ($this->loaded !== true) {
            $this->initGhostlyPlayer();
            return parent::onUpdate($currentTick);
        }

        if ($currentTick % 20 === 0) {
            try {
                $this->getScoreboardSession()->set();
            } catch (Exception $ex) {
                Ghostly::$logger->error("Error in line: {$ex->getLine()}, File: {$ex->getFile()} \n Error: {$ex->getMessage()}");
            }
        }
        return parent::onUpdate($currentTick);
    }

    /**
     * @param int  $index
     * @param Item $item
     */
    public function setItem(int $index, Item $item): void
    {
        $inventory = $this->getInventory();
        if (isset($inventory)) $inventory->setItem($index, $item);
    }

    public function setLobbyItems(): void
    {
        $inventory = $this->getInventory();

        if (isset($inventory)) {
            $inventory->clearAll();

            foreach (['item.navigator' => 0] as $item => $index) {
                $this->setItem($index, ItemsManager::get($item));
            }
        }
    }
}