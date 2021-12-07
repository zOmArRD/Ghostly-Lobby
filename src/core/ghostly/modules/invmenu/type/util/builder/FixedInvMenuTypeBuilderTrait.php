<?php

declare(strict_types=1);

namespace core\ghostly\modules\invmenu\type\util\builder;

use LogicException;

trait FixedInvMenuTypeBuilderTrait
{

    private ?int $size = null;

    protected function getSize(): int
    {
        if ($this->size === null) {
            throw new LogicException("No size was provided");
        }

        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }
}