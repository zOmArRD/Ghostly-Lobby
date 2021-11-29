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

use core\ghostly\Ghostly;
use core\ghostly\modules\mysql\AsyncQuery;
use Exception;
use mysqli;
use pocketmine\Server;

class SelectQuery extends AsyncQuery
{
    /** @var mixed */
    public mixed $rows;

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
        $rows = [];
        try {
            if ($result !== false) {
                while ($row = $result->fetch_assoc()) $rows[] = $row;
                $this->rows = serialize($rows);
            }
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server)
    {
        if ($this->rows === null) {
            Ghostly::$logger->error("Error while executing query. Please check database settings and try again.");
            return;
        }
        $this->rows = unserialize($this->rows);
        parent::onCompletion($server);
    }
}