<?php

declare(strict_types=1);

namespace core\ghostly\modules\mysql;

use mysqli;

class SelectQuery extends AsyncQuery {

    /**
     * @param mysqli $mysqli
     *
     * @return void
     */
    public function query(mysqli $mysqli): void {
        $query = $mysqli->query("SELECT * FROM players WHERE username = 'iTheTrollIdk'");

        if (!$query instanceof \mysqli_result) {
            throw new \RuntimeException('Result error');
        }

        while ($result = $query->fetch_array(MYSQLI_ASSOC)) {
            $this->setResult($result);
        }
    }
}