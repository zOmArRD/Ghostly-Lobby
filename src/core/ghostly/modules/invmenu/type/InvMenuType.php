<?php

declare(strict_types=1);

namespace core\ghostly\modules\invmenu\type;

use core\ghostly\modules\invmenu\InvMenu;
use core\ghostly\modules\invmenu\type\graphic\InvMenuGraphic;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

interface InvMenuType
{

    public function createGraphic(InvMenu $menu, Player $player): ?InvMenuGraphic;

    public function createInventory(): Inventory;
}