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

namespace zomarrd\ghostly\network\player;

use pocketmine\item\Item;
use pocketmine\player\Player;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\items\ItemsManager;
use zomarrd\ghostly\modules\scoreboard\Scoreboard;

final class GhostlyPlayer extends Player
{
    public static array $player_config;
    private bool $loaded = false, $isEditingAnEntity = false;
    private Scoreboard $scoreboardSession;

    /**
     * @param int $currentTick
     *
     * @return bool
     */
    public function onUpdate(int $currentTick): bool
    {
        if ($this->isLoaded() !== true) {
            $this->initPlayer();
            return parent::onUpdate($currentTick);
        }

        if ($currentTick % 20 === 0) {
            $this->getScoreboardSession()->setScore();
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

    /**
     * @return void
     */
    public function initPlayer(): void
    {
        new IPlayer($this);
        new ItemsManager($this);

        $this->scoreboardSession = new Scoreboard($this);
        $this->loaded = true;
    }

    /**
     * @return Scoreboard
     */
    public function getScoreboardSession(): Scoreboard
    {
        return $this->scoreboardSession;
    }

	/**
	 * @return bool
	 */
	public function isEditingAnEntity(): bool
	{
		return $this->isEditingAnEntity;
	}

	/**
	 * @param bool $isEditingAnEntity
	 */
	public function setIsEditingAnEntity(bool $isEditingAnEntity): void
	{
		$this->isEditingAnEntity = $isEditingAnEntity;
		$result = $isEditingAnEntity ?? false;
		if ($isEditingAnEntity) {
			$this->sendMessage(PREFIX . '§a' . 'You activated the mode of editing an entity, when interacting with the entity a menu will open.');
		} else {
			$this->sendMessage(PREFIX . '§a' .'You have disabled the mode of editing an entity!');

		}
	}

    /**
     * @return void
     */
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

    /**
     * @return bool
     */
    public function isOp(): bool
    {
        return GExtension::getServerPM()->isOp($this->getName());
    }
}