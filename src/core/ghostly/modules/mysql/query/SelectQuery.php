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

class SelectQuery extends AsyncQuery
{
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

        $rows = [];

        try {
            if ($result !== false) {
                while ($row = $result->fetch_assoc()) $rows[] = $row;

                $this->setResult($rows);
            }
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}