<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Tests\Types;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\Buffertools;
use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Parser;
use BitWasp\Buffertools\Tests\BinaryTest;
use BitWasp\Buffertools\Types\ByteString;

class ByteStringTest extends BinaryTest
{
    public function getVectors(): array
    {
        return [
            [1, '04'],
            [1, '41'],
            [4, '0488b21e'],
        ];
    }


    /**
     * @dataProvider getVectors
     */
    public function testByteString(int $size, string $string): void
    {
        $buffer = Buffer::hex($string, $size);

        $t = new ByteString($size);
        $out = $t->write($buffer);

        self::assertEquals(\pack("H*", $string), $out);

        $parser = new Parser(new Buffer($out));
        self::assertEquals($string, $t->read($parser)->getHex());
    }


    /**
     * @dataProvider getVectors
     */
    public function testByteStringLe(int $size, string $string): void
    {
        $buffer = Buffer::hex($string, $size);

        $t = new ByteString($size, ByteOrder::LE);
        $out = $t->write($buffer);

        $eFlipped = Buffertools::flipBytesString(\pack("H*", $string));
        self::assertEquals($eFlipped, $out);

        $parser = new Parser(new Buffer($out));
        self::assertEquals($string, $t->read($parser)->getHex());
    }
}
