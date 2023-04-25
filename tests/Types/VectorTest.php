<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Tests\Types;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\Parser;
use BitWasp\Buffertools\Tests\BinaryTest;
use BitWasp\Buffertools\Types\VarInt;
use BitWasp\Buffertools\Types\Vector;

class VectorTest extends BinaryTest
{
    public function testVector(): void
    {
        $varint = new VarInt;
        $vector = new Vector(
            $varint,
            static fn () => null,
        );

        $buffer = Buffer::hex('010203040506070809000a0b0c0d0e0f');
        $array = [$buffer, $buffer, $buffer];
        self::assertEquals(
            '03' . $buffer->getHex() . $buffer->getHex() . $buffer->getHex(),
            \bin2hex($vector->write($array)),
        );
    }


    public function testWriteInvalid(): void
    {
        $varint = new VarInt;
        $vector = new Vector(
            $varint,
            static fn () => null,
        );
        $buffer = Buffer::hex('010203040506070809000a0b0c0d0e0f');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Vector::write() must be supplied with an array');

        $vector->write($buffer);
    }


    public function testReadVector(): void
    {
        $varint = new VarInt;
        $vector = new Vector(
            $varint,
            static fn (Parser $parser) => $parser->readBytes(16)
        );

        $eBuffer = Buffer::hex('010203040506070809000a0b0c0d0e0f');
        $hex = '03010203040506070809000a0b0c0d0e0f010203040506070809000a0b0c0d0e0f010203040506070809000a0b0c0d0e0f';
        $buffer = Buffer::hex($hex);
        $parser = new Parser($buffer);

        $array = $vector->read($parser);

        foreach ($array as $item) {
            self::assertEquals($eBuffer->getBinary(), $item->getBinary());
        }
    }
}
