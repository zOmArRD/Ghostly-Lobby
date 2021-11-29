<?php
/*
 * Created by PhpStorm
 *
 * User: zOmArRD
 * Date: 1/8/2021
 *
 * Copyright © 2021 - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly\modules\mysql\query;

use core\ghostly\modules\mysql\AsyncQuery;
use Exception;
use mysqli;
use pocketmine\Server;

class InsertQuery extends AsyncQuery
{
    /** @var mixed */
    public mixed $res;

    /** @var string */
    public string $query;

    public function __construct(string $sqlQuery)
    {
        $this->query = $sqlQuery;
    }

    /**
     * @param mysqli $mysqli
     */
    public function query(mysqli $mysqli): void
    {
        $result = $mysqli->query($this->query);
        $this->res = serialize($result);
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server)
    {
        try {
            $this->res = unserialize($this->res);
        } catch (Exception) {
            $this->res = null;
        }
        parent::onCompletion($server);
    }
}