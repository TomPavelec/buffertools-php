<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Tests;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\Exceptions\ParserOutOfRange;
use BitWasp\Buffertools\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParserEmpty(): void
    {
        $parser = new Parser;
        self::assertInstanceOf(Parser::class, $parser);

        self::assertSame(0, $parser->getPosition());
        self::assertInstanceOf(Buffer::class, $parser->getBuffer());
        self::assertEmpty($parser->getBuffer()->getHex());
    }


    public function testGetBuffer(): void
    {
        $buffer = Buffer::hex('41414141');

        $parser = new Parser($buffer);
        self::assertSame($parser->getBuffer()->getBinary(), $buffer->getBinary());
    }


    public function testGetBufferEmptyNull(): void
    {
        $buffer = new Buffer;
        $parser = new Parser($buffer);
        $parserData = $parser->getBuffer()->getBinary();
        $bufferData = $buffer->getBinary();
        self::assertSame($parserData, $bufferData);
    }


    public function testWriteBytes(): void
    {
        $bytes = '41424344';
        $parser = new Parser;
        $parser->writeBytes(4, Buffer::hex($bytes));
        $returned = $parser->getBuffer()->getHex();
        self::assertSame($returned, '41424344');
    }


    public function testWriteBytesFlip(): void
    {
        $bytes = '41424344';
        $parser = new Parser;
        $parser->writeBytes(4, Buffer::hex($bytes), true);
        $returned = $parser->getBuffer()->getHex();
        self::assertSame($returned, '44434241');
    }


    public function testWriteBytesPadded(): void
    {
        $parser = new Parser;
        $parser->writeBytes(4, Buffer::hex('34'));
        self::assertEquals("00000034", $parser->getBuffer()->getHex());
    }


    public function testWriteBytesFlipPadded(): void
    {
        $parser = new Parser;
        $parser->writeBytes(4, Buffer::hex('34'), true);
        self::assertEquals("34000000", $parser->getBuffer()->getHex());
    }


    public function testReadBytes(): void
    {
        $bytes = '41424344';

        $parser = new Parser($bytes);
        $read = $parser->readBytes(4);
        self::assertInstanceOf(Buffer::class, $read);

        $hex = $read->getHex();
        self::assertSame($bytes, $hex);
    }


    public function testReadBytesFlip(): void
    {
        $bytes = '41424344';

        $parser = new Parser($bytes);
        $read = $parser->readBytes(4, true);
        self::assertInstanceOf(Buffer::class, $read);

        $hex = $read->getHex();
        self::assertSame('44434241', $hex);
    }


    public function testReadBytesEmpty(): void
    {
        $parser = new Parser;

        $this->expectException(ParserOutOfRange::class);
        $this->expectExceptionMessage('Could not parse string of required length (empty)');

        $data = $parser->readBytes(0);
        self::assertFalse((bool) $data);
    }


    public function testReadBytesEndOfString(): void
    {
        $parser = new Parser('4041414142414141');
        $bytes1 = $parser->readBytes(4);
        $bytes2 = $parser->readBytes(4);
        self::assertSame($bytes1->getHex(), '40414141');
        self::assertSame($bytes2->getHex(), '42414141');

        $this->expectException(ParserOutOfRange::class);
        $this->expectExceptionMessage('Could not parse string of required length (empty)');

        $parser->readBytes(1);
    }


    public function testReadBytesBeyondLength(): void
    {
        $bytes = '41424344';
        $parser = new Parser($bytes);

        $this->expectException(\Exception::class);

        $parser->readBytes(5);
    }
}
