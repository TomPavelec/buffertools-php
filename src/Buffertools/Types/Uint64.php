<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Types;

class Uint64 extends AbstractUint
{
    /**
     * {@inheritDoc}
     *
     * @see TypeInterface::getBitSize()
     */
    public function getBitSize(): int
    {
        return 64;
    }
}
