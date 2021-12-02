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


use pocketmine\player\Player;

final class GhostlyPlayer extends Player
{
    /** @var array */
    public static array $playerSettings = [];
}