<?php

declare(strict_types=1);

namespace core\ghostly\modules\invmenu\type\util\builder;

use core\ghostly\modules\invmenu\type\BlockActorFixedInvMenuType;
use core\ghostly\modules\invmenu\type\graphic\network\BlockInvMenuGraphicNetworkTranslator;
use LogicException;

final class BlockActorFixedInvMenuTypeBuilder implements InvMenuTypeBuilder
{
    use BlockInvMenuTypeBuilderTrait;
    use FixedInvMenuTypeBuilderTrait;
    use GraphicNetworkTranslatableInvMenuTypeBuilderTrait;

    private ?string $block_actor_id = null;

    public function __construct()
    {
        $this->addGraphicNetworkTranslator(BlockInvMenuGraphicNetworkTranslator::instance());
    }

    public function setBlockActorId(string $block_actor_id): self
    {
        $this->block_actor_id = $block_actor_id;
        return $this;
    }

    public function build(): BlockActorFixedInvMenuType
    {
        return new BlockActorFixedInvMenuType($this->getBlock(), $this->getSize(), $this->getBlockActorId(), $this->getGraphicNetworkTranslator());
    }

    private function getBlockActorId(): string
    {
        if ($this->block_actor_id === null) {
            throw new LogicException("No block actor ID was specified");
        }

        return $this->block_actor_id;
    }
}