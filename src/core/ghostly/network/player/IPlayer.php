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

trait IPlayer
{
    /** @var GhostlyPlayer */
    public GhostlyPlayer $player;

    public function __construct(GhostlyPlayer $player)
    {
        $this->setPlayer($player);
    }

    function getPlayerName(): string
    {
        return $this->getPlayer()->getName();
    }

    function getPlayer(): GhostlyPlayer
    {
        return $this->player;
    }

    function setPlayer(GhostlyPlayer $player): void
    {
        $this->player = $player;
    }
}