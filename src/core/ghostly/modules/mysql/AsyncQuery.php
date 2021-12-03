<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 2/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly\modules\mysql;

use mysqli;
use pocketmine\scheduler\AsyncTask;

abstract class AsyncQuery extends AsyncTask
{
    /** @var string */
    public string $host, $user, $password, $database;

    public function onRun(): void
    {
        $this->query($mysqli = new mysqli($this->host, $this->user, $this->password, $this->database));
        if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
        $mysqli->close();
    }

    public function onCompletion(): void
    {
        AsyncQueue::activateCallback($this);
    }

    abstract public function query(mysqli $mysqli): void;
}