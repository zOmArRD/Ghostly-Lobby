<?php

declare(strict_types=1);

namespace core\ghostly\modules\invmenu\type\util\builder;

use LogicException;
use pocketmine\block\Block;

trait BlockInvMenuTypeBuilderTrait
{

    private ?Block $block = null;

    protected function getBlock(): Block
    {
        if ($this->block === null) {
            throw new LogicException("No block was provided");
        }

        return $this->block;
    }

    public function setBlock(Block $block): self
    {
        $this->block = $block;
        return $this;
    }
}