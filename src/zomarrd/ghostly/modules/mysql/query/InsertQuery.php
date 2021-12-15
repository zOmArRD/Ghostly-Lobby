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

namespace zomarrd\ghostly\modules\mysql\query;

use mysqli;
use zomarrd\ghostly\modules\mysql\AsyncQuery;

class InsertQuery extends AsyncQuery
{
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
        $mysqli->query($this->query);
    }
}