<?php

declare(strict_types=1);

namespace BitWasp\Buffertools;

class Buffer implements BufferInterface
{
    protected int $size;
    protected string $buffer;

    /**
     * @throws \Exception
     */
    public function __construct(string $byteString = '', ?int $byteSize = null)
    {
        if ($byteSize !== null) {
            // Check the integer doesn't overflow its supposed size
            if (\strlen($byteString) > $byteSize) {
                throw new \Exception('Byte string exceeds maximum size');
            }
        } else {
            $byteSize = \strlen($byteString);
        }

        $this->size = $byteSize;
        $this->buffer = $byteString;
    }


    /**
     * Create a new buffer from a hex string
     *
     * @return Buffer
     *
     * @throws \Exception
     */
    public static function hex(string $hexString = '', ?int $byteSize = null): BufferInterface
    {
        if (\strlen($hexString) > 0 && !\ctype_xdigit($hexString)) {
            throw new \InvalidArgumentException('Buffer::hex: non-hex character passed');
        }

        $binary = \pack("H*", $hexString);

        return new self($binary, $byteSize);
    }


    /**
     * @return Buffer
     */
    public static function int(int|string $integer, ?int $byteSize = null): BufferInterface
    {
        $gmp = \gmp_init($integer, 10);

        return self::gmp($gmp, $byteSize);
    }


    /**
     * @return Buffer
     *
     * @throws \Exception
     */
    public static function gmp(\GMP $gmp, ?int $byteSize = null): BufferInterface
    {
        if (\gmp_sign($gmp) < 0) {
            throw new \InvalidArgumentException(
                'Negative integers not supported. This could be an application error, or you should be using templates.',
            );
        }

        $hex = \gmp_strval($gmp, 16);

        if ((\mb_strlen($hex) % 2) !== 0) {
            $hex = "0{$hex}";
        }

        $binary = \pack("H*", $hex);

        return new self($binary, $byteSize);
    }


    /**
     * @throws \Exception
     */
    public function slice(int $start, ?int $end = null): BufferInterface
    {
        if ($start > $this->getSize()) {
            throw new \Exception('Start exceeds buffer length');
        }

        if ($end === null) {
            return new self(\substr($this->getBinary(), $start));
        }

        if ($end > $this->getSize()) {
            throw new \Exception('Length exceeds buffer length');
        }

        $string = \substr($this->getBinary(), $start, $end);

        $length = \strlen($string);

        return new self($string, $length);
    }


    /**
     * Get the size of the buffer to be returned
     */
    public function getSize(): int
    {
        return $this->size;
    }


    /**
     * Get the size of the value stored in the buffer
     */
    public function getInternalSize(): int
    {
        return \strlen($this->buffer);
    }


    public function getBinary(): string
    {
        if (\strlen($this->buffer) < $this->size) {
            return \str_pad($this->buffer, $this->size, \chr(0), STR_PAD_LEFT);
        }

        if (\strlen($this->buffer) > $this->size) {
            return \substr($this->buffer, 0, $this->size);
        }

        return $this->buffer;
    }


    public function getHex(): string
    {
        return \unpack("H*", $this->getBinary())[1];
    }


    public function getGmp(): \GMP
    {
        return \gmp_init($this->getHex(), 16);
    }


    public function getInt(): int|string
    {
        return \gmp_strval($this->getGmp(), 10);
    }


    /**
     * @return Buffer
     */
    public function flip(): BufferInterface
    {
        /** @var Buffer $buffer */
        $buffer = Buffertools::flipBytes($this);

        return $buffer;
    }


    public function equals(BufferInterface $other): bool
    {
        return $other->getSize() === $this->getSize()
             && $other->getBinary() === $this->getBinary();
    }


    /**
     * Return a formatted version for var_dump
     */
    public function __debugInfo(): array
    {
        return [
            'size' => $this->size,
            'buffer' => '0x' . \unpack("H*", $this->buffer)[1],
        ];
    }
}
