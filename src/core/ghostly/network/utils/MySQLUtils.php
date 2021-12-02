<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 25/11/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly\network\utils;

use core\ghostly\modules\mysql\AsyncQueue;
use core\ghostly\modules\mysql\query\UpdateRowQuery;

final class MySQLUtils
{
    public static function UpdateRowQuery(array $updates, string $conditionKey, string $conditionValue, string $table)
    {
        AsyncQueue::submitQuery(new UpdateRowQuery($updates, $conditionKey, $conditionValue, $table));
    }
}