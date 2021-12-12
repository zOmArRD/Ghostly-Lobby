<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 11/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly\modules\npc;

use core\ghostly\modules\npc\entity\HumanEntity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;

final class EntityManager
{
    /** @var Human */
    private Human $human;

    public function __construct()
    {
        $this->register();
        $this->human = new Human();
    }

    /**
     * @return void
     */
    public function register(): void
    {
        EntityFactory::getInstance()->register(HumanEntity::class, function (World $world, CompoundTag $nbt): HumanEntity {
            return new HumanEntity(EntityDataHelper::parseLocation($nbt, $world), HumanEntity::parseSkinNBT($nbt), $nbt);
        }, ['HumanEntity']);
    }

    /**
     * @return Human
     */
    public function getHuman(): Human
    {
        return $this->human;
    }
}