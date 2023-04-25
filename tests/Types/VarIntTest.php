<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Tests\Types;

use BitWasp\Buffertools\Tests\BinaryTest;
use BitWasp\Buffertools\Types\VarInt;

class VarIntTest extends BinaryTest
{
    public function testSolveWriteTooLong(): void
    {
        $varint = new VarInt;
        $disallowed = \gmp_add(\gmp_pow(\gmp_init(2, 10), 64), \gmp_init(1, 10));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Integer too large, exceeds 64 bit');

        $varint->solveWriteSize($disallowed);
    }
}
