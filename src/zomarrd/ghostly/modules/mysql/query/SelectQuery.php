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

class SelectQuery extends AsyncQuery
{
    public string $query;
    public mixed $rows;

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
        $rows = [];

        if ($result !== false) {
            while ($row = $result->fetch_assoc()) $rows[] = $row;
            $this->rows = serialize($rows);
        }
    }

    /**
     * @return void
     */
    public function onCompletion(): void
    {
        if ($this->rows === null) {
            return;
        }

        $this->rows = unserialize($this->rows);
        parent::onCompletion();
    }
}