<?php

declare(strict_types=1);

namespace core\ghostly\modules\invmenu\type\util\builder;

use core\ghostly\modules\invmenu\type\InvMenuType;

interface InvMenuTypeBuilder
{

    public function build(): InvMenuType;
}