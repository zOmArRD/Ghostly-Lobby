<?php

declare(strict_types=1);

namespace core\ghostly\modules\invmenu\session;

use core\ghostly\modules\invmenu\InvMenu;
use core\ghostly\modules\invmenu\type\graphic\InvMenuGraphic;

final class InvMenuInfo
{

    public InvMenu $menu;
    public InvMenuGraphic $graphic;

    public function __construct(InvMenu $menu, InvMenuGraphic $graphic)
    {
        $this->menu = $menu;
        $this->graphic = $graphic;
    }
}