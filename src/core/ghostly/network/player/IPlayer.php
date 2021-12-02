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

interface IPlayer
{
    function setPlayer(GhostlyPlayer $player): void;

    function getPlayer(): GhostlyPlayer;

    function getPlayerName(): string;

    public function __construct(GhostlyPlayer $player);
}