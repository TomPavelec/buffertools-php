<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Tests\Types;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\Buffertools;
use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Parser;
use BitWasp\Buffertools\Tests\BinaryTest;
use BitWasp\Buffertools\Types\Uint128;
use BitWasp\Buffertools\Types\Uint16;
use BitWasp\Buffertools\Types\Uint256;
use BitWasp\Buffertools\Types\Uint32;
use BitWasp\Buffertools\Types\Uint64;
use BitWasp\Buffertools\Types\Uint8;
use BitWasp\Buffertools\Types\UintInterface;

class UintSetTest extends BinaryTest
{
    /**
     * @return array
     */
    private function generateSizeBasedTests(int $bitSize, int $byteOrder): array
    {
        $halfPos = \gmp_strval(\gmp_init(\str_pad('7', $bitSize / 4, 'f', STR_PAD_RIGHT), 16), 10);
        $maxPos = \gmp_strval(\gmp_init(\str_pad('', $bitSize / 4, 'f', STR_PAD_RIGHT), 16), 10);

        $test = static function ($integer) use ($bitSize, $byteOrder) {
            $hex = \str_pad(\gmp_strval(\gmp_init($integer, 10), 16), $bitSize / 4, '0', STR_PAD_LEFT);

            if ($byteOrder === ByteOrder::LE) {
                $hex = Buffertools::flipBytes(Buffer::hex($hex))->getHex();
            }

            return [
                $integer,
                $hex,
                null,
            ];
        };

        return [
            $test(0),
            $test(1),
            $test($halfPos),
            $test($maxPos),
        ];
    }


    /**
     * @return UintInterface[]
     */
    public function getUintClasses(): array
    {
        return [
            new Uint8,
            new Uint16,
            new Uint32,
            new Uint64,
            new Uint128,
            new Uint256,
            new Uint8(ByteOrder::LE),
            new Uint16(ByteOrder::LE),
            new Uint32(ByteOrder::LE),
            new Uint64(ByteOrder::LE),
            new Uint128(ByteOrder::LE),
            new Uint256(ByteOrder::LE),
        ];
    }


    public function getAllTests(): array
    {
        $vectors = [];

        foreach ($this->getUintClasses() as $val) {
            $order = $val->getByteOrder();

            foreach ($this->generateSizeBasedTests($val->getBitSize(), $order) as $t) {
                $vectors[] = \array_merge([$val], $t);
            }
        }

        return $vectors;
    }


    /**
     * @dataProvider getAllTests
     */
    public function testUint(UintInterface $comp, int|string $int, string $eHex): void
    {
        $binary = $comp->write($int);
        self::assertEquals($eHex, \str_pad(\bin2hex($binary), $comp->getBitSize() / 4, '0', STR_PAD_LEFT));

        $parser = new Parser(new Buffer($binary));
        $recovered = $comp->read($parser);
        self::assertEquals($int, $recovered);
    }


    public function testUintInvalidOrder(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Must pass valid flag for endianness');

        new Uint8(2);
    }


    public function testInvalidFlipLength(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Bit string length must be a multiple of 8');

        $u = new Uint8(1);
        $u->flipBits('0');
    }
}
