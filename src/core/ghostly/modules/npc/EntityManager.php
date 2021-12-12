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
use pocketmine\entity\EntityFactory;

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
        foreach ([HumanEntity::class] as $class) {
            EntityFactory::getInstance()->register($class);
        }
    }

    /**
     * @return Human
     */
    public function getHuman(): Human
    {
        return $this->human;
    }
}