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

class IPlayer
{
    /** @var GhostlyPlayer */
    public GhostlyPlayer $player;

    /**
     * @param GhostlyPlayer $player
     */
    public function __construct(GhostlyPlayer $player)
    {
        $this->setPlayer($player);
    }

    /**
     * @return string
     */
    public function getPlayerName(): string
    {
        return $this->getPlayer()->getName();
    }

    /**
     * @return GhostlyPlayer
     */
    public function getPlayer(): GhostlyPlayer
    {
        return $this->player;
    }

    /**
     * @param GhostlyPlayer $player
     *
     * @return void
     */
    public function setPlayer(GhostlyPlayer $player): void
    {
        $this->player = $player;
    }
}