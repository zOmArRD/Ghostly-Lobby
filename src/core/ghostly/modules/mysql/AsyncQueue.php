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

namespace core\ghostly\modules\mysql;

use pocketmine\Server;
use const zOmArRD\MySQL;

class AsyncQueue
{
    /** @var array */
    private static array $callbacks = [];

    /**
     * @param AsyncQuery    $asyncQuery
     * @param callable|null $callbackFunction
     */
    static public function submitQuery(AsyncQuery $asyncQuery, ?callable $callbackFunction = null): void
    {
        self::$callbacks[spl_object_hash($asyncQuery)] = $callbackFunction;
        $asyncQuery->host = MySQL['host'];
        $asyncQuery->user = MySQL['user'];
        $asyncQuery->password = MySQL['password'];
        $asyncQuery->database = MySQL['database'];
        Server::getInstance()->getAsyncPool()->submitTask($asyncQuery);
    }

    /**
     * @param AsyncQuery $asyncQuery
     */
    static public function activateCallback(AsyncQuery $asyncQuery): void
    {
        $callable = self::$callbacks[spl_object_hash($asyncQuery)] ?? null;
        if (is_callable($callable)) $callable($asyncQuery["rows"]);
    }
}