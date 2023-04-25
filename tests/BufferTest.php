<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Tests;

use BitWasp\Buffertools\Buffer;
use PHPUnit\Framework\TestCase;

class BufferTest extends TestCase
{
    public function testBufferDebug(): void
    {
        $buffer = new Buffer('AAAA', 4);
        $debug = $buffer->__debugInfo();
        self::assertTrue(isset($debug['buffer']));
        self::assertTrue(isset($debug['size']));

        $str = $debug['buffer'];
        self::assertEquals('0x', \substr((string) $str, 0, 2));
        self::assertEquals('41414141', \substr((string) $str, 2));
    }


    public function testCreateEmptyBuffer(): void
    {
        $buffer = new Buffer;
        self::assertInstanceOf(Buffer::class, $buffer);
        self::assertEmpty($buffer->getBinary());
    }


    public function testCreateEmptyHexBuffer(): void
    {
        $buffer = Buffer::hex();
        self::assertInstanceOf(Buffer::class, $buffer);
        self::assertEmpty($buffer->getBinary());
    }


    public function testCreateBuffer(): void
    {
        $hex = '80000000';
        $buffer = Buffer::hex($hex);
        self::assertInstanceOf(Buffer::class, $buffer);
        self::assertNotEmpty($buffer->getBinary());
    }


    public function testCreateMaxBufferExceeded(): void
    {
        $lim = 4;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Byte string exceeds maximum size');

        Buffer::hex('4141414111', $lim);
    }


    public function testCreateHexBuffer(): void
    {
        $hex = '41414141';
        $buffer = Buffer::hex($hex);
        self::assertInstanceOf(Buffer::class, $buffer);
        self::assertNotEmpty($buffer->getBinary());
    }


    public function testPadding(): void
    {
        $buffer = Buffer::hex('41414141', 6);

        self::assertEquals(4, $buffer->getInternalSize());
        self::assertEquals(6, $buffer->getSize());
        self::assertEquals("000041414141", $buffer->getHex());
    }


    public function testSerialize(): void
    {
        $hex = '41414141';
        $dec = \gmp_strval(\gmp_init($hex, 16), 10);
        $bin = \pack("H*", $hex);
        $buffer = Buffer::hex($hex);

        // Check Binary
        $retBinary = $buffer->getBinary();
        self::assertSame($bin, $retBinary);

        // Check Hex
        self::assertSame($hex, $buffer->getHex());

        // Check Decimal
        self::assertSame($dec, $buffer->getInt());
        self::assertInstanceOf(\GMP::class, $buffer->getGmp());
    }


    public function testGetSize(): void
    {
        self::assertEquals(1, Buffer::hex('41')->getSize());
        self::assertEquals(4, Buffer::hex('41414141')->getSize());
        self::assertEquals(4, Buffer::hex('41', 4)->getSize());
    }


    public function getIntVectors(): array
    {
        return [
            ['1', '01', 1, ],
            ['1', '01', null,],
            ['20', '14', 1, ],
        ];
    }


    /**
     * @dataProvider getIntVectors
     */
    public function testIntConstruct(int|string $int, string $expectedHex, ?int $size = null): void
    {
        $buffer = Buffer::int($int, $size);
        self::assertEquals($expectedHex, $buffer->getHex());
    }


    public function getGmpVectors(): array
    {
        return [
            [ \gmp_init('0A', 16) ],
            [ \gmp_init('237852977508946591877284351678975096651401224047304305322504192889595623579202', 10) ],
        ];
    }


    /**
     * @dataProvider getGmpVectors
     */
    public function testGmpConstruction(\GMP $gmp): void
    {
        self::assertTrue(\gmp_cmp($gmp, Buffer::gmp($gmp)->getGmp()) === 0);
    }


    public function testGmpConstructionNegative(): void
    {
        $gmp = \gmp_init('-1234', 10);

        $this->expectException(\InvalidArgumentException::class);
        Buffer::gmp($gmp);
    }


    public function testSlice(): void
    {
        $a = Buffer::hex("11000011");
        self::assertEquals("1100", $a->slice(0, 2)->getHex());
        self::assertEquals("0011", $a->slice(2, 4)->getHex());

        $b = Buffer::hex("00111100");
        self::assertEquals("0011", $b->slice(0, 2)->getHex());
        self::assertEquals("1100", $b->slice(2, 4)->getHex());

        $c = Buffer::hex("111100", 4);
        self::assertEquals("0011", $c->slice(0, 2)->getHex());
        self::assertEquals("1100", $c->slice(2, 4)->getHex());
    }


    public function testEquals(): void
    {
        $first = Buffer::hex('ab');
        $second = Buffer::hex('ab');
        $firstExtraLong = Buffer::hex('ab', 10);
        $firstShort = new Buffer('', 0);
        self::assertTrue($first->equals($second));
        self::assertFalse($first->equals($firstExtraLong));
        self::assertFalse($first->equals($firstExtraLong));
        self::assertFalse($first->equals($firstShort));
    }
}
