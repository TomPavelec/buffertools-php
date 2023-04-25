<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Tests\Types;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Parser;
use BitWasp\Buffertools\Tests\BinaryTest;
use BitWasp\Buffertools\Types\Int32;
use BitWasp\Buffertools\Types\SignedIntInterface;

class IntSetTest extends BinaryTest
{
    public function getIntSetVectors(): array
    {
        $int32_le = new Int32(ByteOrder::LE);
        $int32_be = new Int32(ByteOrder::BE);

        return [
            [$int32_be, '1', '00000001'],
            [$int32_le, '1', '01000000'],
            [$int32_be, '-1', 'ffffffff'],
            [$int32_le, '-1', 'ffffffff'],
            [$int32_be, '0', '00000000'],
            [$int32_le, '0', '00000000'],
        ];
    }


    /**
     * @dataProvider getIntSetVectors
     */
    public function testInt(SignedIntInterface $signed, int|string $int, string $expectedHex): void
    {
        $out = $signed->write($int);
        self::assertEquals($expectedHex, \str_pad(\bin2hex($out), $signed->getBitSize() / 4, '0', STR_PAD_LEFT));

        $parser = new Parser(new Buffer($out));
        $recovered = $signed->read($parser);
        self::assertEquals($int, $recovered);
    }
}
