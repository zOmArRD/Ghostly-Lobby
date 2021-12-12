/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 11/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
CREATE TABLE IF NOT EXISTS player_config (
    xuid VARCHAR(50),
    player VARCHAR(16),
    language VARCHAR(10),
    setScoreboard SMALLINT DEFAULT 1,
    chestGui SMALLINT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS network_servers(server VARCHAR(15), players INT, is_online SMALLINT, is_maintenance SMALLINT, is_whitelisted SMALLINT);