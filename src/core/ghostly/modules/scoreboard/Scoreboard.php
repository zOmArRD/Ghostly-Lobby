<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 29/11/2021
 *
 * Copyright © 2021 GhostlyMC Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace core\ghostly\modules\scoreboard;

use core\ghostly\network\resources\ResourcesManager;
use core\ghostly\network\utils\TextUtils;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

/**
 * @todo finalize this
 */
final class Scoreboard extends ScoreboardAPI
{
    /** @var string[] This is to replace blanks */
    private const EMPTY_CACHE = ["§0\e", "§1\e", "§2\e", "§3\e", "§4\e", "§5\e", "§6\e", "§7\e", "§8\e", "§9\e", "§a\e", "§b\e", "§c\e", "§d\e", "§e\e"];

    /**
     * @param int    $line
     * @param string $message
     *
     * @return string
     */
    public function replaceData(int $line, string $message): string
    {
        if (empty($message)) return self::EMPTY_CACHE[$line] ?? "";
        $msg = $message;

        $data = [
            "{black}" => TextFormat::BLACK,
            "{dark.blue}" => TextFormat::DARK_BLUE,
            "{dark.green}" => TextFormat::DARK_GREEN,
            "{dark.aqua}" => TextFormat::DARK_AQUA,
            "{dark.red}" => TextFormat::DARK_RED,
            "{dark.purple}" => TextFormat::DARK_PURPLE,
            "{gold}" => TextFormat::GOLD,
            "{gray}" => TextFormat::GRAY,
            "{dark.gray}" => TextFormat::DARK_GRAY,
            "{blue}" => TextFormat::BLUE,
            "{green}" => TextFormat::GREEN,
            "{aqua}" => TextFormat::AQUA,
            "{red}" => TextFormat::RED,
            "{light.purple}" => TextFormat::LIGHT_PURPLE,
            "{yellow}" => TextFormat::YELLOW,
            "{white}" => TextFormat::WHITE,
            "{obfuscated}" => TextFormat::OBFUSCATED,
            "{bold}" => TextFormat::BOLD,
            "{strikethrough}" => TextFormat::STRIKETHROUGH,
            "{underline}" => TextFormat::UNDERLINE,
            "{italic}" => TextFormat::ITALIC,
            "{reset}" => TextFormat::RESET,
            "{eol}" => TextFormat::EOL,
            "{player.get.name}" => $this->getPlayerName(),
            "{date}" => date("d/m/y"),
            "{time}" => date("G:ia"),
        ];

        $keys = array_keys($data);
        $values = array_values($data);

        for ($i = 0; $i < count($keys); $i++) $msg = str_replace($keys[$i], (string)$values[$i], $msg);
        return $msg;
    }

    /**
     * @return Config
     */
    private function getScoreboardFile(): Config
    {
        return ResourcesManager::getFile("scoreboard.yml");
    }

    /**
     * Update each line of the scoreboard.
     */
    private function update(): void
    {
        if (!is_array($this->getScoreboardFile()->get("lines")))return;
        foreach ($this->getScoreboardFile()->get("lines") as $scLine => $string) {
            $line = $scLine +1;
            $msg = $this->replaceData($line, (string)$string);
            $this->setLine($line, $msg);
        }
    }

    public function set(): void
    {
        /* TODO: add verification of player settings via MySQL */
        if ($this->getScoreboardFile()->get("is.enabled") !== "true") return;
        $this->new("ghostly.lobby", TextUtils::colorize($this->getScoreboardFile()->get("display.name")));
        $this->update();
    }
}