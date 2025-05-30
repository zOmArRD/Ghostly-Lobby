<?php

declare(strict_types=1);

namespace muqsit\simplepackethandler;

use InvalidArgumentException;
use muqsit\simplepackethandler\interceptor\IPacketInterceptor;
use muqsit\simplepackethandler\interceptor\PacketInterceptor;
use muqsit\simplepackethandler\monitor\IPacketMonitor;
use muqsit\simplepackethandler\monitor\PacketMonitor;
use pocketmine\event\EventPriority;
use pocketmine\plugin\Plugin;

final class SimplePacketHandler
{

    /**
     * @param Plugin $registerer
     * @param int    $priority
     * @param bool   $handleCancelled
     *
     * @return IPacketInterceptor
     */
    public static function createInterceptor(Plugin $registerer, int $priority = EventPriority::NORMAL, bool $handleCancelled = false): IPacketInterceptor
    {
        if ($priority === EventPriority::MONITOR) {
            throw new InvalidArgumentException('Cannot intercept packets at MONITOR priority');
        }
        return new PacketInterceptor($registerer, $priority, $handleCancelled);
    }

    /**
     * @param Plugin $registerer
     * @param bool   $handleCancelled
     *
     * @return IPacketMonitor
     */
    public static function createMonitor(Plugin $registerer, bool $handleCancelled = false): IPacketMonitor
    {
        return new PacketMonitor($registerer, $handleCancelled);
    }
}