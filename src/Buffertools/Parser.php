<?php

declare(strict_types=1);

namespace BitWasp\Buffertools;

use BitWasp\Buffertools\Exceptions\ParserOutOfRange;

class Parser
{
    private string $string;
    private int $size;
    private int $position;

    /**
     * Instantiate class, optionally taking Buffer or HEX.
     */
    public function __construct(null|string|BufferInterface $input = null)
    {
        if ($input === null) {
            $input = '';
        }

        $bin = \is_string($input)
            ? Buffer::hex($input, null)->getBinary()
            : $input->getBinary();

        $this->string = $bin;
        $this->position = 0;
        $this->size = \strlen($this->string);
    }


    /**
     * Get the position pointer of the parser - ie, how many bytes from 0
     */
    public function getPosition(): int
    {
        return $this->position;
    }


    /**
     * Get the total size of the parser
     */
    public function getSize(): int
    {
        return $this->size;
    }


    /**
     * Parse $bytes bytes from the string, and return the obtained buffer
     *
     * @throws \Exception
     */
    public function readBytes(int $numBytes, bool $flipBytes = false): BufferInterface
    {
        $string = \substr($this->string, $this->getPosition(), $numBytes);
        $length = \strlen($string);

        if ($length === 0) {
            throw new ParserOutOfRange('Could not parse string of required length (empty)');
        }

        if ($length < $numBytes) {
            throw new ParserOutOfRange('Could not parse string of required length (too short)');
        }

        $this->position += $numBytes;

        if ($flipBytes) {
            $string = Buffertools::flipBytesString($string);
            /** @var string $string */
        }

        return new Buffer($string, $length);
    }


    /**
     * Write $data as $bytes bytes. Can be flipped if needed.
     *
     * @param int $numBytes - number of bytes to write
     * @param SerializableInterface|BufferInterface|string $data - buffer, serializable or hex
     */
    public function writeBytes(
        int $numBytes,
        SerializableInterface|BufferInterface|string $data,
        bool $flipBytes = false,
    ): self
    {
        // Treat $data to ensure it's a buffer, with the correct size
        if ($data instanceof SerializableInterface) {
            $data = $data->getBuffer();
        }

        if (\is_string($data)) {
            // Convert to a buffer
            $data = Buffer::hex($data, $numBytes);
        }

        $this->writeBuffer($numBytes, $data, $flipBytes);

        return $this;
    }


    /**
     * Write $data as $bytes bytes. Can be flipped if needed.
     */
    public function writeRawBinary(int $numBytes, string $data, bool $flipBytes = false): self
    {
        return $this->writeBuffer($numBytes, new Buffer($data, $numBytes), $flipBytes);
    }


    public function writeBuffer(int $numBytes, BufferInterface $buffer, bool $flipBytes = false): self
    {
        // only create a new buffer if the size does not match
        if ($buffer->getSize() !== $numBytes) {
            $buffer = new Buffer($buffer->getBinary(), $numBytes);
        }

        $this->appendBuffer($buffer, $flipBytes);

        return $this;
    }


    public function appendBuffer(BufferInterface $buffer, bool $flipBytes = false): self
    {
        $this->appendBinary($buffer->getBinary(), $flipBytes);

        return $this;
    }


    public function appendBinary(string $binary, bool $flipBytes = false): self
    {
        if ($flipBytes) {
            $binary = Buffertools::flipBytesString($binary);
        }

        $this->string .= $binary;
        $this->size += \strlen($binary);

        return $this;
    }


    /**
     * Take an array containing serializable objects.
     *
     * @param SerializableInterface[]|BufferInterface[] $serializable
     */
    public function writeArray(array $serializable): self
    {
        $parser = new self(Buffertools::numToVarInt(\count($serializable)));

        foreach ($serializable as $object) {
            if ($object instanceof SerializableInterface) {
                $object = $object->getBuffer();
            }

            $parser->writeBytes($object->getSize(), $object);
        }

        $this->string .= $parser->getBuffer()->getBinary();
        $this->size += $parser->getSize();

        return $this;
    }


    /**
     * Return the string as a buffer
     */
    public function getBuffer(): BufferInterface
    {
        return new Buffer($this->string, null);
    }
}
