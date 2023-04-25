<?php

declare(strict_types=1);

namespace BitWasp\Buffertools;

use BitWasp\Buffertools\Types\ByteString;
use BitWasp\Buffertools\Types\Int128;
use BitWasp\Buffertools\Types\Int16;
use BitWasp\Buffertools\Types\Int256;
use BitWasp\Buffertools\Types\Int32;
use BitWasp\Buffertools\Types\Int64;
use BitWasp\Buffertools\Types\Int8;
use BitWasp\Buffertools\Types\Uint128;
use BitWasp\Buffertools\Types\Uint16;
use BitWasp\Buffertools\Types\Uint256;
use BitWasp\Buffertools\Types\Uint32;
use BitWasp\Buffertools\Types\Uint64;
use BitWasp\Buffertools\Types\Uint8;
use BitWasp\Buffertools\Types\VarInt;
use BitWasp\Buffertools\Types\VarString;
use BitWasp\Buffertools\Types\Vector;

class CachingTypeFactory extends TypeFactory
{
    protected array $cache = [];

    /**
     * Add a Uint8 serializer to the template
     */
    public function uint8(): Uint8
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint8();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Uint8 serializer to the template
     */
    public function uint8le(): Uint8
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint8le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a Uint16 serializer to the template
     */
    public function uint16(): Uint16
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint16();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Uint16 serializer to the template
     */
    public function uint16le(): Uint16
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint16le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a Uint32 serializer to the template
     */
    public function uint32(): Uint32
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint32();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Uint32 serializer to the template
     */
    public function uint32le(): Uint32
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint32le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a Uint64 serializer to the template
     */
    public function uint64(): Uint64
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint64();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Uint64 serializer to the template
     */
    public function uint64le(): Uint64
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint64le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a Uint128 serializer to the template
     */
    public function uint128(): Uint128
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint128();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Uint128 serializer to the template
     */
    public function uint128le(): Uint128
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint128le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a Uint256 serializer to the template
     */
    public function uint256(): Uint256
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint256();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Uint256 serializer to the template
     */
    public function uint256le(): Uint256
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::uint256le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a int8 serializer to the template
     */
    public function int8(): Int8
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int8();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Int8 serializer to the template
     */
    public function int8le(): Int8
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int8le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a int16 serializer to the template
     */
    public function int16(): Int16
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int16();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Int16 serializer to the template
     */
    public function int16le(): Int16
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int16le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a int32 serializer to the template
     */
    public function int32(): Int32
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int32();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Int serializer to the template
     */
    public function int32le(): Int32
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int32le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a int64 serializer to the template
     */
    public function int64(): Int64
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int64();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Int64 serializer to the template
     */
    public function int64le(): Int64
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int64le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a int128 serializer to the template
     */
    public function int128(): Int128
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int128();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Int128 serializer to the template
     */
    public function int128le(): Int128
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int128le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a int256 serializer to the template
     */
    public function int256(): Int256
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int256();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a little-endian Int256 serializer to the template
     */
    public function int256le(): Int256
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::int256le();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a VarInt serializer to the template
     */
    public function varint(): VarInt
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::varint();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a VarString serializer to the template
     */
    public function varstring(): VarString
    {
        if (!isset($this->cache[__FUNCTION__])) {
            $this->cache[__FUNCTION__] = parent::varstring();
        }

        return $this->cache[__FUNCTION__];
    }


    /**
     * Add a byte string serializer to the template. This serializer requires a length to
     * pad/truncate to.
     */
    public function bytestring(int $length): ByteString
    {
        $name = __FUNCTION__ . $length;

        if (!isset($this->cache[$name])) {
            $this->cache[$name] = parent::bytestring(...\func_get_args());
        }

        return $this->cache[$name];
    }


    /**
     * Add a little-endian byte string serializer to the template. This serializer requires
     * a length to pad/truncate to.
     */
    public function bytestringle(int $length): ByteString
    {
        $name = __FUNCTION__ . $length;

        if (!isset($this->cache[$name])) {
            $this->cache[$name] = parent::bytestringle(...\func_get_args());
        }

        return $this->cache[$name];
    }


    /**
     * Add a vector serializer to the template. A $readHandler must be provided if the
     * template will be used to deserialize a vector, since it's contents are not known.
     *
     * The $readHandler should operate on the parser reference, reading the bytes for each
     * item in the collection.
     */
    public function vector(callable $readHandler): Vector
    {
        return parent::vector($readHandler);
    }
}
