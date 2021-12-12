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
    /** @var array */
    public static array $player_config;
    /** @var bool */
    private bool $loaded = false;
    /** @var Scoreboard */
    private Scoreboard $scoreboardSession;

    public function onUpdate(int $currentTick): bool
    {
        if ($this->isLoaded() !== true) {
            $this->initGhostlyPlayer();
            return parent::onUpdate($currentTick);
        }

        if ($currentTick % 20 === 0) {
            $this->getScoreboardSession()->set();
        }
        return parent::onUpdate($currentTick);
    }

    /**
     * @return bool
     */
    public function isLoaded(): bool
    {
        return $this->loaded;
    }

    public function initGhostlyPlayer(): void
    {
        new IPlayer($this);
        new ItemsManager($this);

        $this->scoreboardSession = new Scoreboard($this);
        $this->loaded = true;
    }

    public function getScoreboardSession(): Scoreboard
    {
        return $this->scoreboardSession;
    }

    public function setLobbyItems(): void
    {
        $inventory = $this->getInventory();

        if (isset($inventory)) {
            $inventory->clearAll();

            foreach (['item.navigator' => 0, 'item.cosmetics' => 1, 'item.ls' => 8] as $item => $index) {
                $this->setItem($index, ItemsManager::get($item));
            }
        }
    }

    /**
     * @param int       $index
     * @param Item|null $item
     *
     * @return void
     */
    public function setItem(int $index = 0, ?Item $item = null): void
    {
        $inventory = $this->getInventory();
        $inventory?->setItem($index, $item);
    }
}