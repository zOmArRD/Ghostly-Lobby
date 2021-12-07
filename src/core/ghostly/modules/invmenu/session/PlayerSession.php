<?php

declare(strict_types=1);

namespace core\ghostly\modules\invmenu\session;

use Closure;
use core\ghostly\modules\invmenu\session\network\PlayerNetwork;
use pocketmine\player\Player;

final class PlayerSession
{

    protected Player $player;
    protected PlayerNetwork $network;
    protected ?InvMenuInfo $current = null;

    public function __construct(Player $player, PlayerNetwork $network)
    {
        $this->player = $player;
        $this->network = $network;
    }

    /**
     * @internal
     */
    public function finalize(): void
    {
        if ($this->current !== null) {
            $this->current->graphic->remove($this->player);
            $this->player->removeCurrentWindow();
        }
        $this->network->dropPending();
    }

    public function getCurrent(): ?InvMenuInfo
    {
        return $this->current;
    }

    /**
     * @param InvMenuInfo|null             $current
     * @param Closure|null                 $callback
     *
     * @phpstan-param Closure(bool) : void $callback
     * @internal use InvMenu::send() instead.
     *
     */
    public function setCurrentMenu(?InvMenuInfo $current, ?Closure $callback = null): void
    {
        $this->current = $current;

        if ($this->current !== null) {
            $this->network->waitUntil($this->network->getGraphicWaitDuration(), function (bool $success) use ($callback): void {
                if ($this->current !== null) {
                    if ($success && $this->current->graphic->sendInventory($this->player, $this->current->menu->getInventory())) {
                        if ($callback !== null) {
                            $callback(true);
                        }
                        return;
                    }

                    $this->removeCurrentMenu();
                    if ($callback !== null) {
                        $callback(false);
                    }
                }
            });
        } else {
            $this->network->wait($callback ?? static function (bool $success): void {
                });
        }
    }

    /**
     * @return bool
     * @internal use Player::removeCurrentWindow() instead
     */
    public function removeCurrentMenu(): bool
    {
        if ($this->current !== null) {
            $this->current->graphic->remove($this->player);
            $this->setCurrentMenu(null);
            return true;
        }
        return false;
    }

    public function getNetwork(): PlayerNetwork
    {
        return $this->network;
    }
}