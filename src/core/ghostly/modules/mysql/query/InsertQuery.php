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

class InsertQuery extends AsyncQuery
{
    /** @var mixed */
    public mixed $res;

    /** @var string */
    public string $query;

    /**
     * @param string $sqlQuery
     */
    public function __construct(string $sqlQuery)
    {
        $this->query = $sqlQuery;
    }

    /**
     * @param mysqli $mysqli
     *
     * @return void
     */
    public function query(mysqli $mysqli): void
    {
        $result = $mysqli->query($this->query);
        $this->res = serialize($result);
    }

    /**
     * @return void
     */
    public function onCompletion(): void
    {
        try {
            $this->res = unserialize($this->res);
        } catch (Exception) {
            $this->res = null;
        }
    }
}