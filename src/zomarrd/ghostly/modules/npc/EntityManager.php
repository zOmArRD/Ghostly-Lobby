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
use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\modules\form\SimpleForm;
use zomarrd\ghostly\modules\npc\entity\HumanEntity;
use zomarrd\ghostly\network\player\GhostlyPlayer;
use zomarrd\ghostly\network\player\lang\TranslationsKeys;

final class EntityManager
{
    /** @var Human */
    private static Human $human;

    public function __construct()
    {
        $this->register();
        self::$human = new Human();
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
	 * @return void
	 */
	public static function purgeAllEntities(): void
	{
		foreach (GExtension::getWorldManager()->getWorlds() as $world) {
			foreach ($world->getEntities() as $entity) {
				if (!$entity instanceof GhostlyPlayer) {
					$entity->kill();
				}
			}
		}
	}

    /**
     * @param HumanEntity   $entity
     * @param GhostlyPlayer $player
     *
     * @return void
     */
    public static function showHumanEntityForm(HumanEntity $entity, GhostlyPlayer $player): void
    {
		$entityName = $entity->getSkin()->getSkinId();

		$form = new SimpleForm(function (GhostlyPlayer $player, $data) use ($entityName){
			if (isset($data)) {
				switch ($data) {
					case 'close': return;
					case 'kill_entity':
						self::getHuman()->kill($entityName);
						break;
					default:
						$player->sendMessage(TranslationsKeys::BUTTON_UNRECOGNIZED);
						break;
				}
			}
        });

        $form->setTitle('§fEditing Entity: §b' . $entityName);

        $form->setContent('Categories to edit:');

        $form->addButton('Change NameTag', $form::IMAGE_TYPE_NULL, '', 'change_nametag');
		$form->addButton('Kill Entity', $form::IMAGE_TYPE_NULL, '', 'kill_entity');
		$form->addButton('Close', $form::IMAGE_TYPE_NULL, '', 'close');

		$player->sendForm($form);
    }

    /**
     * @return Human
     */
    public static function getHuman(): Human
    {
        return self::$human;
    }
}