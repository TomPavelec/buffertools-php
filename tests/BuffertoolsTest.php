<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Tests;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\Buffertools;
use PHPUnit\Framework\TestCase;

class BuffertoolsTest extends TestCase
{
    private function getUnsortedList(): array
    {
        return [
            '0101',
            '4102',
            'a43e',
            '0000',
            '0120',
            'd01b',
        ];
    }


    private function getSortedList(): array
    {
        return [
            '0000',
            '0101',
            '0120',
            '4102',
            'a43e',
            'd01b',
        ];
    }


    private function getUnsortedBufferList(): array
    {
        $results = [];

        foreach ($this->getUnsortedList() as $hex) {
            $results[] = Buffer::hex($hex);
        }

        return $results;
    }


    private function getSortedBufferList(): array
    {
        $results = [];

        foreach ($this->getSortedList() as $hex) {
            $results[] = Buffer::hex($hex);
        }

        return $results;
    }


    public function testSortDefault(): void
    {
        $items = $this->getUnsortedBufferList();
        $v = Buffertools::sort($items);

        self::assertEquals($this->getSortedBufferList(), $v);
    }


    public function testSortCallable(): void
    {
        $items = $this->getUnsortedList();
        $sorted = Buffertools::sort($items, static fn ($a) => Buffer::hex($a));

        self::assertEquals($this->getSortedList(), $sorted);
    }


    public function testNumToVarInt(): void
    {
        // Should not prefix with anything. Just return chr($decimal);
        for ($i = 0; $i < 253; $i++) {
            $decimal = 1;
            $expected = \chr($decimal);
            $val = Buffertools::numToVarInt($decimal)->getBinary();

            self::assertSame($expected, $val);
        }
    }


    public function testNumToVarInt1LowerFailure(): void
    {
        // This decimal should NOT return a prefix
        $decimal = 0xfc; // 252;
        $val = Buffertools::numToVarInt($decimal)->getBinary();
        self::assertSame($val[0], \chr(0xfc));
    }


    public function testNumToVarInt1Lowest(): void
    {
        // Decimal > 253 requires a prefix
        $decimal = 0xfd;
        $expected = \chr(0xfd).\chr(0xfd).\chr(0x00);
        $val = Buffertools::numToVarInt($decimal);//->getBinary();
        self::assertSame($expected, $val->getBinary());
    }


    public function testNumToVarInt1Upper(): void
    {
        // This prefix is used up to 0xffff, because if we go higher,
        // the prefixes are no longer in agreement
        $decimal = 0xffff;
        $expected = \chr(0xfd) . \chr(0xff) . \chr(0xff);
        $val = Buffertools::numToVarInt($decimal)->getBinary();
        self::assertSame($expected, $val);
    }


    public function testNumToVarInt2LowerFailure(): void
    {
        // We can check that numbers this low don't yield a 0xfe prefix
        $decimal = 0xfffe;
        $expected = \chr(0xfe) . \chr(0xfe) . \chr(0xff);
        $val = Buffertools::numToVarInt($decimal);

        self::assertNotSame($expected, $val);
    }


    public function testNumToVarInt2Lowest(): void
    {
        // With this prefix, check that the lowest for this field IS prefictable.
        $decimal = 0xffff0001;
        $expected = \chr(0xfe) . \chr(0x01) . \chr(0x00) . \chr(0xff) . \chr(0xff);
        $val = Buffertools::numToVarInt($decimal);

        self::assertSame($expected, $val->getBinary());
    }


    public function testNumToVarInt2Upper(): void
    {
        // Last number that will share 0xfe prefix: 2^32
        $decimal = 0xffffffff;
        $expected = \chr(0xfe) . \chr(0xff) . \chr(0xff) . \chr(0xff) . \chr(0xff);
        $val = Buffertools::numToVarInt($decimal);//->getBinary();

        self::assertSame($expected, $val->getBinary());
    }


    public function testFlipBytes(): void
    {
        $buffer = Buffer::hex('41');
        $string = $buffer->getBinary();
        $flip = Buffertools::flipBytesString($string);
        self::assertSame($flip, $string);

        $buffer = Buffer::hex('4141');
        $string = $buffer->getBinary();
        $flip = Buffertools::flipBytesString($string);
        self::assertSame($flip, $string);

        $buffer = Buffer::hex('4142');
        $string = $buffer->getBinary();
        $flip = Buffertools::flipBytesString($string);
        self::assertSame($flip, \chr(0x42) . \chr(0x41));

        $buffer = Buffer::hex('0102030405060708');
        $string = $buffer->getBinary();
        $flip = Buffertools::flipBytesString($string);
        self::assertSame(
            $flip,
            \chr(0x08) . \chr(0x07) . \chr(0x06) . \chr(0x05) . \chr(0x04) . \chr(0x03) . \chr(0x02) . \chr(0x01),
        );
    }


    public function testConcat(): void
    {
        $a = Buffer::hex("1100");
        $b = Buffer::hex("0011");
        $c = Buffer::hex("11", 2);

        self::assertEquals("11000011", Buffertools::concat($a, $b)->getHex());
        self::assertEquals("00111100", Buffertools::concat($b, $a)->getHex());

        self::assertEquals("11000011", Buffertools::concat($a, $c)->getHex());
        self::assertEquals("00111100", Buffertools::concat($c, $a)->getHex());
    }
}
