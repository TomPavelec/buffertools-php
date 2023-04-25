<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Types;

class Uint8 extends AbstractUint
{
    /**
     * {@inheritDoc}
     *
     * @see TypeInterface::getBitSize()
     */
    public function getBitSize(): int
    {
        return 8;
    }
}
