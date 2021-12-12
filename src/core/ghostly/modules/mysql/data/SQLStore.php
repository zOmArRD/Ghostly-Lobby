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

namespace core\ghostly\modules\mysql\data;

use core\ghostly\modules\mysql\AsyncQueue;
use core\ghostly\modules\mysql\query\InsertQuery;

class SQLStore
{
    /** @var string Create the table where the player's data is stored. */
    private const CREATE_CONFIG = "CREATE TABLE IF NOT EXISTS player_config(xuid VARCHAR(50), player VARCHAR(16), language VARCHAR(10), setScoreboard SMALLINT DEFAULT 1, chestGui SMALLINT DEFAULT 0);";

    /** @var string Create the table of servers for the network. */
    private const CREATE_SERVERS = "CREATE TABLE IF NOT EXISTS network_servers(server VARCHAR(15), players INT, is_online SMALLINT, is_maintenance SMALLINT, is_whitelisted SMALLINT);";

    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        foreach ([self::CREATE_CONFIG, self::CREATE_SERVERS] as $query) {
            AsyncQueue::runAsync(new InsertQuery($query));
        }
    }
}