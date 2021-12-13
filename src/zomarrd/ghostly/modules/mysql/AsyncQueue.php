<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 9/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\modules\mysql;

use pocketmine\Server;

final class AsyncQueue
{
    /** @var array */
    private static array $callbacks = [];

    /**
     * @param AsyncQuery    $query
     * @param callable|null $callable
     *
     * @return void
     */
    static public function runAsync(AsyncQuery $query, ?callable $callable = null): void
    {
        $query->host = MySQL['host'];
        $query->user = MySQL['user'];
        $query->pass = MySQL['password'];
        $query->db = MySQL['database'];

        self::$callbacks[spl_object_hash($query)] = $callable;
        Server::getInstance()->getAsyncPool()->submitTask($query);
    }

    /**
     * @param AsyncQuery $query
     *
     * @return void
     */
    static public function submitAsync(AsyncQuery $query): void
    {
        $callable = self::$callbacks[spl_object_hash($query)] ?? null;
        if (is_callable($callable)) {
            $callable($query["rows"]);
        }
    }
}