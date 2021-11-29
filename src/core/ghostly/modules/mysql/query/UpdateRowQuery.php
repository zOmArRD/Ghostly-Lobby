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

namespace core\ghostly\modules\mysql\query;

use core\ghostly\Ghostly;
use core\ghostly\modules\mysql\AsyncQuery;
use mysqli;

class UpdateRowQuery extends AsyncQuery
{
    /** @var string|null */
    public ?string $table, $updates, $conditionKey, $conditionValue;

    /**
     * @param array       $updates
     * @param string      $conditionKey
     * @param string      $conditionValue
     * @param string|null $table
     */
    public function __construct(array $updates, string $conditionKey, string $conditionValue, string $table = null)
    {
        $this->updates = serialize($updates);
        $this->conditionKey = $conditionKey;
        $this->conditionValue = $conditionValue;

        if ($table === null) {
            Ghostly::$logger->error("Unable to update the changes in the database");
            return;
        }
        $this->table = $table;
    }

    /**
     * @param mysqli $mysqli
     */
    public function query(mysqli $mysqli): void
    {
        $updates = [];
        foreach (unserialize($this->updates) as $k => $v) $updates[] = "$k='$v'";
        $mysqli->query("UPDATE $this->table SET " . implode(",", $updates) . " WHERE $this->conditionKey='$this->conditionValue';");
    }
}