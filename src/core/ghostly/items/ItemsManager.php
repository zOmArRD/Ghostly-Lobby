<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 5/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly\items;

use core\ghostly\network\player\IPlayer;
use pocketmine\block\Block;
use pocketmine\entity\Attribute;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

final class ItemsManager extends IPlayer
{

    /**
     * @param string $item
     *
     * @return Attribute|Block|Item|null
     */
    public static function get(string $item): Attribute|Block|Item|null
    {
        return match ($item) {
            "item.navigator" => self::load(ItemIds::COMPASS, "§cServer Selector §7(Click)"),
            "item.cosmetics" => self::load(ItemIds::ENDER_CHEST, "§cCosmetics §7(Click)"),
            "item.ls" => self::load(ItemIds::NETHER_STAR, "§cLobby Selector §7(Click)"),
            default => ItemFactory::air(),
        };

    }

    /**
     * @param int    $itemId
     * @param string $customName
     *
     * @return Block|Attribute|Item|null
     */
    public static function load(int $itemId, string $customName): Attribute|Block|Item|null
    {
        return ItemFactory::getInstance()->get($itemId)->setCustomName($customName);
    }
}