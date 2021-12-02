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

namespace core\ghostly\modules\scoreboard;

use core\ghostly\Ghostly;
use core\ghostly\network\player\GhostlyPlayer;
use core\ghostly\network\player\IPlayer;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;

abstract class ScoreboardAPI implements IPlayer
{
    /** @var GhostlyPlayer */
    private GhostlyPlayer $player;

    /** @var array */
    public array $lines = [], $objectiveName = [];

    public function setPlayer(GhostlyPlayer $player): void
    {
        $this->player = $player;
    }

    public function getPlayer(): GhostlyPlayer
    {
        return $this->player;
    }

    public function getPlayerName(): string
    {
        return $this->getPlayer()->getName();
    }

    public function __construct(GhostlyPlayer $player)
    {
        $this->setPlayer($player);
    }

    public function setObjectiveName(string $objectiveName): void
    {
        $this->objectiveName[$this->getPlayerName()] = $objectiveName;
    }

    public function getObjectiveName(): string
    {
        return $this->objectiveName[$this->getPlayerName()];
    }

    public function removeObjectiveName(): void
    {
        unset($this->objectiveName[$this->getPlayerName()]);
    }

    public function isObjectiveName(): bool
    {
        return isset($this->objectiveName[$this->getPlayer()->getName()]);
    }

    public function remove(): void
    {
        $packet = new RemoveObjectivePacket();
        $packet->objectiveName = $this->getObjectiveName();
        $this->getPlayer()->getNetworkSession()->sendDataPacket($packet);
    }

    public function new(string $objectiveName, string $displayName): void
    {
        if ($this->isObjectiveName()) $this->remove();

        $packet = new SetDisplayObjectivePacket();
        $packet->objectiveName = $objectiveName;
        $packet->displayName = $displayName;
        $packet->sortOrder = 0;
        $packet->displaySlot = "sidebar";
        $packet->criteriaName = "dummy";
        $this->setObjectiveName($objectiveName);
        $this->getPlayer()->getNetworkSession()->sendDataPacket($packet);
    }

    public function setLine(int $score, string $message): void
    {
        if (!$this->isObjectiveName()) return;

        if ($score > 15 || $score < 0) {
            Ghostly::$logger->error("Score must be between the value of 1-15. $score out of range.");
            return;
        }

        $entry = new ScorePacketEntry();
        $entry->objectiveName = $this->getObjectiveName();
        $entry->type = $entry::TYPE_FAKE_PLAYER;
        if (isset($this->lines[$score])) {
            $packet1 = new SetScorePacket();
            $packet1->entries[] = $this->lines[$score];
            $packet1->type = $packet1::TYPE_REMOVE;
            $this->getPlayer()->getNetworkSession()->sendDataPacket($packet1);
            unset($this->lines[$score]);
        }
        $entry->score = $score;

        $entry->scoreboardId = $score;
        $entry->customName = $message;
        $this->lines[$score] = $entry;

        $packet2 = new SetScorePacket();
        $packet2->entries[] = $entry;
        $packet2->type = $packet2::TYPE_CHANGE;
        $this->getPlayer()->getNetworkSession()->sendDataPacket($packet2);
    }
}