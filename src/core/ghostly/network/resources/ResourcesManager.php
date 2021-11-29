<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 29/11/2021
 *
 * Copyright © 2021 GhostlyMC Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly\network\resources;

use core\ghostly\Ghostly;
use core\ghostly\network\GExtension;

final class ResourcesManager
{
    private array $listFiles = ['config.yml', 'scoreboard.yml'];
    public function init(): void
    {
        Ghostly::$logger->info("Resource management has started!");
        @mkdir(Ghostly::getGhostly()->getDataFolder());

        foreach ($this->listFiles as $file) {
            Ghostly::getGhostly()->saveResource($file);
        }


    }
}