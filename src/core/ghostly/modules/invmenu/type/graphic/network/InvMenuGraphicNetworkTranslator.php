<?php

declare(strict_types=1);

namespace core\ghostly\modules\invmenu\type\graphic\network;

use core\ghostly\modules\invmenu\session\InvMenuInfo;
use core\ghostly\modules\invmenu\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

interface InvMenuGraphicNetworkTranslator
{

    public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet): void;
}