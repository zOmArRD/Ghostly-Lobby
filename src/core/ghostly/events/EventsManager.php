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

use core\ghostly\events\listener\InteractListener;
use core\ghostly\events\listener\PlayerListener;
use core\ghostly\events\listener\WorldListener;
use core\ghostly\Ghostly;

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
        Ghostly::$logger->info(PREFIX . "§a" . "loading the Events...");
        foreach ([new PlayerListener(), new WorldListener(), new InteractListener()] as $listener) $this->register($listener);
    }
}