<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 16/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\modules\npc\event;

use pocketmine\event\Event;
use zomarrd\ghostly\modules\npc\entity\HumanEntity;
use zomarrd\ghostly\network\player\GhostlyPlayer;

class HumanEntityHitEvent extends Event
{
    /** @var HumanEntity */
    private HumanEntity $entity;
    /** @var GhostlyPlayer */
    private GhostlyPlayer $player;

    /**
     * @param HumanEntity   $entity
     * @param GhostlyPlayer $player
     */
    public function __construct(HumanEntity $entity, GhostlyPlayer $player)
    {
        $this->entity = $entity;
        $this->player = $player;
    }

    /**
     * @return HumanEntity
     */
    public function getEntity(): HumanEntity
    {
        return $this->entity;
    }

    /**
     * @return GhostlyPlayer
     */
    public function getPlayer(): GhostlyPlayer
    {
        return $this->player;
    }
}