<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 2/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly\events;

use core\ghostly\events\listener\PlayerListener;
use core\ghostly\Ghostly;
use Exception;

final class EventsManager extends Events
{

    public function __construct()
    {
        $this->loadEvents();
    }

    /**
     * In this function you add the events to the foreach array to register them.
     */
    public function loadEvents(): void
    {
        foreach ([new PlayerListener()] as $listener) $this->register($listener);
        Ghostly::$logger->info(PREFIX . "the events have been registered!");
    }
}