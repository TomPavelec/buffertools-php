<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Types;

use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Exceptions\ParserOutOfRange;
use BitWasp\Buffertools\Parser;

abstract class AbstractUint extends AbstractType implements UintInterface
{
    public function __construct(int $byteOrder = ByteOrder::BE)
    {
        parent::__construct($byteOrder);
    }


    /**
     * @param int|string $integer - decimal integer
     */
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
        $bits = \str_pad(
            \gmp_strval(\gmp_init($parser->readBytes($bitSize / 8)->getHex(), 16), 2),
            $bitSize,
            '0',
            STR_PAD_LEFT,
        );

        $finalBits = $this->isBigEndian()
            ? $bits
            : $this->flipBits($bits);

        return \gmp_strval(\gmp_init($finalBits, 2), 10);
    }


    /**
     * {@inheritDoc}
     *
     * @see TypeInterface::write()
     */
    public function write(mixed $value): string
    {
        return \pack(
            "H*",
            \str_pad(
                \gmp_strval(
                    \gmp_init(
                        $this->isBigEndian()
                        ? $this->writeBits($value)
                        : $this->flipBits($this->writeBits($value)),
                        2,
                    ),
                    16,
                ),
                $this->getBitSize()/4,
                '0',
                STR_PAD_LEFT,
            ),
        );
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
