<?php

declare(strict_types=1);

namespace BitWasp\Buffertools;

class TemplateFactory
{
    private Template $template;
    private TypeFactoryInterface|CachingTypeFactory $types;

    /**
     * TemplateFactory constructor.
     */
    public function __construct(?Template $template = null, ?TypeFactoryInterface $typeFactory = null)
    {
        $this->template = $template ?? new Template;
        $this->types = $typeFactory ?? new CachingTypeFactory;
    }


    /**
     * Return the Template as it stands.
     */
    public function getTemplate(): Template
    {
        return $this->template;
    }


    /**
     * Add a Uint8 serializer to the template
     */
    public function uint8(): self
    {
        $this->template->addItem($this->types->uint8());

        return $this;
    }


    /**
     * Add a little-endian Uint8 serializer to the template
     */
    public function uint8le(): self
    {
        $this->template->addItem($this->types->uint8le());

        return $this;
    }


    /**
     * Add a Uint16 serializer to the template
     */
    public function uint16(): self
    {
        $this->template->addItem($this->types->uint16());

        return $this;
    }


    /**
     * Add a little-endian Uint16 serializer to the template
     */
    public function uint16le(): self
    {
        $this->template->addItem($this->types->uint16le());

        return $this;
    }


    /**
     * Add a Uint32 serializer to the template
     */
    public function uint32(): self
    {
        $this->template->addItem($this->types->uint32());

        return $this;
    }


    /**
     * Add a little-endian Uint32 serializer to the template
     */
    public function uint32le(): self
    {
        $this->template->addItem($this->types->uint32le());

        return $this;
    }


    /**
     * Add a Uint64 serializer to the template
     */
    public function uint64(): self
    {
        $this->template->addItem($this->types->uint64());

        return $this;
    }


    /**
     * Add a little-endian Uint64 serializer to the template
     */
    public function uint64le(): self
    {
        $this->template->addItem($this->types->uint64le());

        return $this;
    }


    /**
     * Add a Uint128 serializer to the template
     */
    public function uint128(): self
    {
        $this->template->addItem($this->types->uint128());

        return $this;
    }


    /**
     * Add a little-endian Uint128 serializer to the template
     */
    public function uint128le(): self
    {
        $this->template->addItem($this->types->uint128le());

        return $this;
    }


    /**
     * Add a Uint256 serializer to the template
     */
    public function uint256(): self
    {
        $this->template->addItem($this->types->uint256());

        return $this;
    }


    /**
     * Add a little-endian Uint256 serializer to the template
     */
    public function uint256le(): self
    {
        $this->template->addItem($this->types->uint256le());

        return $this;
    }


    /**
     * Add a int8 serializer to the template
     */
    public function int8(): self
    {
        $this->template->addItem($this->types->int8());

        return $this;
    }


    /**
     * Add a little-endian Int8 serializer to the template
     */
    public function int8le(): self
    {
        $this->template->addItem($this->types->int8le());

        return $this;
    }


    /**
     * Add a int16 serializer to the template
     */
    public function int16(): self
    {
        $this->template->addItem($this->types->int16());

        return $this;
    }


    /**
     * Add a little-endian Int16 serializer to the template
     */
    public function int16le(): self
    {
        $this->template->addItem($this->types->int16le());

        return $this;
    }


    /**
     * Add a int32 serializer to the template
     */
    public function int32(): self
    {
        $this->template->addItem($this->types->int32());

        return $this;
    }


    /**
     * Add a little-endian Int serializer to the template
     */
    public function int32le(): self
    {
        $this->template->addItem($this->types->int32le());

        return $this;
    }


    /**
     * Add a int64 serializer to the template
     */
    public function int64(): self
    {
        $this->template->addItem($this->types->int64());

        return $this;
    }


    /**
     * Add a little-endian Int64 serializer to the template
     */
    public function int64le(): self
    {
        $this->template->addItem($this->types->int64le());

        return $this;
    }


    /**
     * Add a int128 serializer to the template
     */
    public function int128(): self
    {
        $this->template->addItem($this->types->int128());

        return $this;
    }


    /**
     * Add a little-endian Int128 serializer to the template
     */
    public function int128le(): self
    {
        $this->template->addItem($this->types->int128le());

        return $this;
    }


    /**
     * Add a int256 serializer to the template
     */
    public function int256(): self
    {
        $this->template->addItem($this->types->int256());

        return $this;
    }


    /**
     * Add a little-endian Int256 serializer to the template
     */
    public function int256le(): self
    {
        $this->template->addItem($this->types->int256le());

        return $this;
    }


    /**
     * Add a VarInt serializer to the template
     */
    public function varint(): self
    {
        $this->template->addItem($this->types->varint());

        return $this;
    }


    /**
     * Add a VarString serializer to the template
     */
    public function varstring(): self
    {
        $this->template->addItem($this->types->varstring());

        return $this;
    }


    /**
     * Add a byte string serializer to the template. This serializer requires a length to
     * pad/truncate to.
     */
    public function bytestring(int $length): self
    {
        $this->template->addItem($this->types->bytestring($length));

        return $this;
    }


    /**
     * Add a little-endian byte string serializer to the template. This serializer requires
     * a length to pad/truncate to.
     */
    public function bytestringle(int $length): self
    {
        $this->template->addItem($this->types->bytestringle($length));

        return $this;
    }


    /**
     * Add a vector serializer to the template. A $readHandler must be provided if the
     * template will be used to deserialize a vector, since it's contents are not known.
     *
     * The $readHandler should operate on the parser reference, reading the bytes for each
     * item in the collection.
     */
    public function vector(callable $readHandler): self
    {
        $this->template->addItem($this->types->vector($readHandler));

        return $this;
    }
}
