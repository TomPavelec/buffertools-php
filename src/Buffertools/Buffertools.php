<?php

declare(strict_types=1);

namespace BitWasp\Buffertools;

class Buffertools
{
    /**
     * @throws \Exception
     */
    public static function numToVarIntBin(int $decimal): string
    {
        if ($decimal < 0xfd) {
            $bin = \chr($decimal);
        } elseif ($decimal <= 0xffff) {
            // Uint16
            $bin = \pack("Cv", 0xfd, $decimal);
        } elseif ($decimal <= 0xffffffff) {
            // Uint32
            $bin = \pack("CV", 0xfe, $decimal);
        } else {
            // Todo, support for 64bit integers
            throw new \Exception('numToVarInt(): Integer too large');
        }

        return $bin;
    }


    /**
     * Convert a decimal number into a VarInt Buffer
     *
     * @throws \Exception
     */
    public static function numToVarInt(int $decimal): BufferInterface
    {
        return new Buffer(static::numToVarIntBin($decimal));
    }


    /**
     * Flip byte order of this binary string.
     */
    public static function flipBytes(
        BufferInterface $bytes,
    ): BufferInterface
    {
        return new Buffer(
            self::flipBytesString(
                $bytes->getBinary(),
            ),
        );
    }


    /**
     * Flip byte order of this binary string.
     */
    public static function flipBytesString(
        string $bytes,
    ): string
    {
        return \implode('', \array_reverse(\str_split($bytes, 1)));
    }


    public static function concat(
        BufferInterface $buffer1,
        BufferInterface $buffer2,
        ?int $size = null,
    ): BufferInterface
    {
        return new Buffer($buffer1->getBinary() . $buffer2->getBinary(), $size);
    }


    /**
     *  What if we don't have two buffers, or want to guard the types of the
     * sorting algorithm?
     *
     * The default behaviour should be, take a list of Buffers/SerializableInterfaces, and
     * sort their binary representation.
     *
     * If an anonymous function is provided, we completely defer the conversion of values to
     * Buffer to the $convertToBuffer callable.
     *
     * This is to allow anonymous functions which are responsible for converting the item to a buffer,
     * and which optionally type-hint the items in the array.
     *
     * @param array $items
     */
    public static function sort(array $items, ?callable $convertToBuffer = null): array
    {
        if ($convertToBuffer === null) {
            $convertToBuffer = static function ($value) {
                if ($value instanceof BufferInterface) {
                    return $value;
                }

                if ($value instanceof SerializableInterface) {
                    return $value->getBuffer();
                }

                throw new \RuntimeException('Requested to sort unknown type');
            };
        }

        \usort($items, static function ($a, $b) use ($convertToBuffer) {
            $av = $convertToBuffer($a)->getBinary();
            $bv = $convertToBuffer($b)->getBinary();

            return $av === $bv
                ? 0
                : ($av > $bv ? 1 : -1);
        });

        return $items;
    }
}
