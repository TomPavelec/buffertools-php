<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Types;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Exceptions\ParserOutOfRange;
use BitWasp\Buffertools\Parser;

abstract class AbstractSignedInt extends AbstractType implements SignedIntInterface
{
    public function __construct(int $byteOrder = ByteOrder::BE)
    {
        parent::__construct($byteOrder);
    }


    public function writeBits(int|string $integer): string
    {
        return \str_pad(
            \gmp_strval(\gmp_init($integer, 10), 2),
            $this->getBitSize(),
            '0',
            STR_PAD_LEFT,
        );
    }


    /**
     * @throws ParserOutOfRange
     * @throws \Exception
     */
    public function readBits(Parser $parser): string
    {
        $bitSize = $this->getBitSize();
        $byteSize = $bitSize / 8;

        $bytes = $parser->readBytes($byteSize);
        $bytes = $this->isBigEndian()
            ? $bytes
            : $bytes->flip();
        $chars = $bytes->getBinary();

        $offsetIndex = 0;
        $isNegative = (\ord($chars[$offsetIndex]) & 0x80) !== 0x00;
        $number = \gmp_init(\ord($chars[$offsetIndex++]) & 0x7F, 10);

        for ($i = 0; $i < $byteSize-1; $i++) {
            $number = \gmp_or(\gmp_mul($number, 0x100), \ord($chars[$offsetIndex++]));
        }

        if ($isNegative) {
            $number = \gmp_sub($number, \gmp_pow(2, $bitSize - 1));
        }

        return \gmp_strval($number, 10);
    }


    /**
     * {@inheritDoc}
     *
     * @see TypeInterface::write()
     */
    public function write(mixed $value): string
    {
        $bitSize = $this->getBitSize();

        if (\gmp_sign($value) < 0) {
            $value = \gmp_add($value, (\gmp_sub(\gmp_pow(2, $bitSize), 1)));
            $value = \gmp_add($value, 1);
        }

        $binary = Buffer::hex(\str_pad(\gmp_strval($value, 16), $bitSize/4, '0', STR_PAD_LEFT), $bitSize/8);

        if (!$this->isBigEndian()) {
            $binary = $binary->flip();
        }

        return $binary->getBinary();
    }


    /**
     * {@inheritDoc}
     *
     * @see TypeInterface::read()
     */
    public function read(Parser $parser): string
    {
        return $this->readBits($parser);
    }
}
