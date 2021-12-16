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

use pocketmine\entity\Skin;
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\modules\npc\entity\HumanEntity;
use zomarrd\ghostly\network\player\GhostlyPlayer;

final class Human
{

    /**
     * @param string        $npcId
     * @param GhostlyPlayer $player
     * @param string        $nameTag
     * @param bool          $spawnToAll
     *
     * @return void
     */
    public function spawn(string $npcId, GhostlyPlayer $player, bool $spawnToAll = true, string $nameTag = ''): void
    {
        $location = $player->getLocation();
        $skin = $player->getSkin();

        foreach ($location->getWorld()->getEntities() as $entity) if ($entity instanceof HumanEntity) {
            if ($entity->getSkin()->getSkinId() === $npcId) $entity->kill();
        }

        $human = new HumanEntity($location, new Skin($npcId, $skin->getSkinData(), $skin->getCapeData(), $skin->getGeometryName(), $skin->getGeometryData()));

		$human->setNameTag("§r" . $nameTag);
        $human->setImmobile();
        if ($spawnToAll) {
            $human->spawnToAll();
        } else {
            $human->spawnTo($player);
        }
    }

    /**
     * @param string $npcId
     * @param string $nameTag
     *
     * @return void
     */
    public function setNameTag(string $npcId, string $nameTag): void
    {
        foreach (GExtension::getWorldManager()->getDefaultWorld()->getEntities() as $entity) if ($entity instanceof HumanEntity) {
            if ($entity->getSkin()->getSkinId() === $npcId) $entity->setNameTag($nameTag);
        }
    }

    /**
     * @param string $npcId
     *
     * @return void
     */
    public function kill(string $npcId): void
    {
        foreach (GExtension::getWorldManager()->getDefaultWorld()->getEntities() as $entity) if ($entity instanceof HumanEntity) {
            if ($entity->getSkin()->getSkinId() === $npcId) $entity->kill();
        }
    }
}