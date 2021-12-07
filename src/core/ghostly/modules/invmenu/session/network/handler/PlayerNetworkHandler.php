<?php

declare(strict_types=1);

namespace core\ghostly\modules\invmenu\session\network\handler;

use Closure;
use core\ghostly\modules\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler
{

    public function createNetworkStackLatencyEntry(Closure $then): NetworkStackLatencyEntry;
}