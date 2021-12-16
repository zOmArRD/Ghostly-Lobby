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

namespace zomarrd\ghostly\modules\npc;

use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use zomarrd\ghostly\modules\form\Form;
use zomarrd\ghostly\modules\form\SimpleForm;
use zomarrd\ghostly\modules\npc\entity\HumanEntity;
use zomarrd\ghostly\network\player\GhostlyPlayer;

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

    /**
     * @param HumanEntity   $entity
     * @param GhostlyPlayer $player
     *
     * @return void
     */
    public function showHumanEntityForm(HumanEntity $entity, GhostlyPlayer $player): void
    {
        $form = new SimpleForm(function (GhostlyPlayer $player, $data){
            var_dump($data);
        });

        $form->setTitle("Test");
        $form->setContent("test form");
        $form->addButton("aaa", $form::IMAGE_TYPE_NULL, "", "test");
        $player->sendForm($form);
    }
}