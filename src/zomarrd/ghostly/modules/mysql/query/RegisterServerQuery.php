<?php
/*
 * Created by PhpStorm.
 *  
 * User: zOmArRD
 * Date: 20/7/2021
 *  
 * Copyright © 2021 Greek Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\modules\mysql\query;

use mysqli;
use zomarrd\ghostly\modules\mysql\AsyncQuery;

class RegisterServerQuery extends AsyncQuery
{
    /** @var string */
    public string $serverName;

    /**
     * @param string $serverName
     */
    public function __construct(string $serverName)
    {
        $this->serverName = $serverName;
    }

    /**
     * @param mysqli $mysqli
     *
     * @return void
     */
    public function query(mysqli $mysqli): void
    {
        $result = $mysqli->query("SELECT * FROM network_servers WHERE server='$this->serverName';");

        $assoc = $result->fetch_assoc();

        if (!is_array($assoc)) {
            $mysqli->query("INSERT INTO network_servers(server, is_online, is_maintenance, is_whitelisted, players) VALUES ('$this->serverName', 1, 0, 0, 0);");
            return;
        }

        $mysqli->query("UPDATE network_servers SET is_online = 1 WHERE server='$this->serverName';");
    }
}