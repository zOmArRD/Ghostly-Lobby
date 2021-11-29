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

use Exception;
use pocketmine\utils\TextFormat;

final class TextUtils
{
    /**
     * This function is in charge of searching for the keys that are defined here below,
     * and replaces them with the colors of PocketMine-MP.
     *
     * @param string $text
     *
     * @return string The text with the color applied.
     * @example TextUtils::replaceColor("{green}Hi Sir, how are u today");
     */
    public static function colorize(string $text): string
    {
        $m = $text;

        $colors = ["{black}" => TextFormat::BLACK,
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
            "{eol}" => TextFormat::EOL];

        $keys = array_keys($colors);
        $values = array_values($colors);

        for ($i = 0; $i < count($keys); $i++) $m = str_replace($keys[$i], (string)$values[$i], $m);

        return $m ?? "";
    }

    /**
     * It can help you change something in a text | message
     *
     * @param string $msg
     * @param array  $array
     *
     * @return string
     * @example TextUtils::replaceVars("Hi my name is {player.name}", ["{player.name}" => "Pedro"]);
     *
     */
    public static function replaceVars(string $msg, array $array): string
    {
        $m = $msg;
        $keys = array_keys($array);
        $values = array_values($array);

        for ($i = 0; $i < count($keys); $i++) $m = str_replace($keys[$i], $values[$i], $m);
        return $m;
    }

    /**
     * Decode an uuencoded string
     * 
     * @param string $id
     *
     * @return bool|string
     */
    public static function uDecode(string $id): bool|string
    {
        try {
            return convert_uudecode($id);
        } catch (Exception) {
            return "error";
        }
    }
}