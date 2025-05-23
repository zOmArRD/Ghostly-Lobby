<?php

declare(strict_types=1);

namespace muqsit\simplepackethandler\monitor;

use Closure;
use pocketmine\plugin\Plugin;
use ReflectionException;

final class PacketMonitor implements IPacketMonitor
{

    private PacketMonitorListener $listener;

    public function __construct(Plugin $register, bool $handleCancelled)
    {
        $this->listener = new PacketMonitorListener($register, $handleCancelled);
    }

    /**
     * @param Closure $handler
     *
     * @return IPacketMonitor
     * @throws ReflectionException
     */
    public function monitorIncoming(Closure $handler): IPacketMonitor
    {
        $this->listener->monitorIncoming($handler);
        return $this;
    }

    /**
     * @param Closure $handler
     *
     * @return IPacketMonitor
     * @throws ReflectionException
     */
    public function monitorOutgoing(Closure $handler): IPacketMonitor
    {
        $this->listener->monitorOutgoing($handler);
        return $this;
    }
}