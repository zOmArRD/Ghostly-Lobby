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

use mysqli;
use pocketmine\scheduler\AsyncTask;

abstract class AsyncQuery extends AsyncTask
{
    /** @var string */
    public string $host, $user, $pass, $db;

    /**
     * @return void
     */
    public function onRun(): void
    {
        $this->query($mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db));
        if ($mysqli->connect_errno) die(PREFIX . "Could not connect to the database!");
        $mysqli->close();
    }

    /**
     * @param mysqli $mysqli
     *
     * @return void
     */
    abstract public function query(mysqli $mysqli): void;

    /**
     * @return void
     */
    public function onCompletion(): void
    {
        AsyncQueue::submitAsync($this);
    }
}