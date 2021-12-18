<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 20/7/2021
 *
 * Copyright © 2021 Greek Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\network\server;

use zomarrd\ghostly\GExtension;
use zomarrd\ghostly\modules\mysql\AsyncQueue;
use zomarrd\ghostly\modules\mysql\query\SelectQuery;
use zomarrd\ghostly\modules\mysql\query\UpdateRowQuery;

class Server
{
	public function __construct(
		private string $name,
		private int    $players = 0,
		private bool   $online = false,
		private bool   $whitelisted = false
	){}

	/**
	 * Synchronize server data from database.
	 *
	 * @return void
	 */
	public function sync(): void
	{
		AsyncQueue::runAsync(new SelectQuery("SELECT * FROM network_servers WHERE server='';"), function ($rows) {
			$row = $rows[0];
			if ($row !== null) {
				$this->setOnline((bool)$row['is_online']);
				$this->setPlayers((int)$row['players']);
				$this->setWhitelisted((bool)$row['is_whitelisted']);
				$this->setMaintenance((bool)$row['is_maintenance']);
			} else {
				$this->setOnline(false);
				$this->setPlayers(0);
				$this->setWhitelisted(false);
				$this->setMaintenance(false);
			}
		});
	}

	/**
	 * @param bool $value
	 *
	 * @return void
	 */
	public function setOnline(bool $value): void
	{
		if (!$value) {
			AsyncQueue::runAsync(new UpdateRowQuery(['is_online' => 0, 'players' => 0], 'server', $this->getName(), 'network_servers'));
		}
		$this->online = $value;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param int $players
	 *
	 * @return void
	 */
	public function setPlayers(int $players): void
	{
		$this->players = $players;
	}

	/**
	 * @param bool $whitelisted
	 *
	 * @return void
	 */
	public function setWhitelisted(bool $whitelisted): void
	{
		$this->whitelisted = $whitelisted;
	}

	/**
	 * @return void
	 * @todo Add if the server is under maintenance.
	 */
	public function update(): void
	{
		$players = count(GExtension::getServerPM()->getOnlinePlayers());
		$isWhitelist = GExtension::getServerPM()->hasWhitelist() ? 1 : 0;
		AsyncQueue::runAsync(new UpdateRowQuery(['players' => $players, 'is_whitelisted' => $isWhitelist], 'server', $this->getName(), 'network_servers'));
	}

	/**
	 * @return string
	 */
	public function getStatus(): string
	{
		return $this->isOnline() ? ('§a' . 'PLAYING: §f' . $this->getPlayers()) : ('§c' . 'OFFLINE');
	}

	/**
	 * @return bool
	 */
	public function isOnline(): bool
	{
		return $this->online;
	}

	/**
	 * @return int
	 */
	public function getPlayers(): int
	{
		return $this->players;
	}

	/**
	 * @param string $name
	 *
	 * @return void
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}


	/**
	 * @return bool
	 */
	public function isWhitelisted(): bool
	{
		return $this->whitelisted;
	}
}