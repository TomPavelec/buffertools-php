<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Types;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\ByteOrder;
use BitWasp\Buffertools\Parser;

class ByteString extends AbstractType
{
    public function __construct(private int $length, int $byteOrder = ByteOrder::BE)
    {
        parent::__construct($byteOrder);
    }


    public function writeBits(BufferInterface $string): string
    {
        return \str_pad(
            \gmp_strval(\gmp_init($string->getHex(), 16), 2),
            $this->length * 8,
            '0',
            STR_PAD_LEFT,
        );
    }


    /**
     * @throws \Exception
     */
    public function write(mixed $value): string
    {
        if (!($value instanceof Buffer)) {
            throw new \InvalidArgumentException('FixedLengthString::write() must be passed a Buffer');
        }

        $bits = $this->isBigEndian()
            ? $this->writeBits($value)
            : $this->flipBits($this->writeBits($value));

        $hex = \str_pad(
            \gmp_strval(\gmp_init($bits, 2), 16),
            $this->length * 2,
            '0',
            STR_PAD_LEFT,
        );

        return \pack("H*", $hex);
    }


    public function readBits(BufferInterface $buffer): string
    {
        return \str_pad(
            \gmp_strval(\gmp_init($buffer->getHex(), 16), 2),
            $this->length * 8,
            '0',
            STR_PAD_LEFT,
        );
    }


    /**
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     */
    public function read(Parser $parser): BufferInterface
    {
        $bits = $this->readBits($parser->readBytes($this->length));

        if (!$this->isBigEndian()) {
            $bits = $this->flipBits($bits);
        }

        return Buffer::hex(
            \str_pad(
                \gmp_strval(\gmp_init($bits, 2), 16),
                $this->length * 2,
                '0',
                STR_PAD_LEFT,
            ),
            $this->length,
        );
    }
}
