<?php

declare(strict_types=1);

namespace BitWasp\Buffertools;

class ByteOrder
{
    /**
     * Little endian means bytes must be flipped
     */
    final public const LE = 0;

    /**
     * Assuming machine byte order?
     */
    final public const BE = 1;
}
