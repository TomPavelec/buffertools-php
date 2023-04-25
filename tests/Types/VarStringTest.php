<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Tests\Types;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\Exceptions\ParserOutOfRange;
use BitWasp\Buffertools\Parser;
use BitWasp\Buffertools\Tests\BinaryTest;
use BitWasp\Buffertools\Types\VarInt;
use BitWasp\Buffertools\Types\VarString;

class VarStringTest extends BinaryTest
{
    public function getSampleVarStrings(): array
    {
        return \array_map(static fn (string $value) => [$value], [
            '',
            '00',
            '00010203040506070809',
            '00010203040506070809000102030405060708090001020304050607080900010203040506070809000102030405060708090001020304050607080900010203040506070809000102030405060708090001020304050607080900010203040506070809000102030405060708090001020304050607080900010203040506070809000102030405060708090001020304050607080900010203040506070809000102030405060708090001020304050607080900010203040506070809000102030405060708090001020304050607080900010203040506070809000102030405060708090001020304050607080900010203040506070809000102',
        ]);
    }


    /**
     * @throws ParserOutOfRange
     * @throws \Exception
     *
     * @dataProvider getSampleVarStrings
     */
    public function testGetVarString(string $input): void
    {
        $varstring = new VarString(new VarInt);
        $binary = $varstring->write(Buffer::hex($input));

        $parser = new Parser(new Buffer($binary));
        $original = $varstring->read($parser);

        self::assertSame($input, $original->getHex());
    }


    public function testAbortsWithInvalidVarIntLength(): void
    {
        $buffer = new Buffer("\x05\x00");

        $varstring = new VarString(new VarInt);

        $this->expectException(ParserOutOfRange::class);
        $this->expectExceptionMessage('Insufficient data remaining for VarString');

        $varstring->read(new Parser($buffer));
    }


    public function testFailsWithoutBuffer(): void
    {
        $varstring = new VarString(new VarInt);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Must provide a buffer');

        $varstring->write('');
    }
}
