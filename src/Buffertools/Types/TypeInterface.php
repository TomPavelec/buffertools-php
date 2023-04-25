<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Types;

use BitWasp\Buffertools\Parser;

interface TypeInterface
{
    /**
     * Flip whatever bitstring is given to us
     */
    public function flipBits(string $bitString): string;


    public function write(mixed $value): string;


    public function read(Parser $parser): mixed;


    public function getByteOrder(): int;
}
