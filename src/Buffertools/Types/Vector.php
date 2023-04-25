<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Types;

use BitWasp\Buffertools\Parser;

class Vector extends AbstractType
{
    /** @var callable */
    private $readFxn;

    public function __construct(private VarInt $varint, callable $readFunction)
    {
        $this->readFxn = $readFunction;

        parent::__construct($varint->getByteOrder());
    }


    /**
     * {@inheritDoc}
     *
     * @see TypeInterface::write()
     */
    public function write(mixed $value): string
    {
        if (\is_array($value) === false) {
            throw new \InvalidArgumentException('Vector::write() must be supplied with an array');
        }

        $parser = new Parser;

        return $parser
            ->writeArray($value)
            ->getBuffer()
            ->getBinary();
    }


    /**
     * {@inheritDoc}
     *
     * @see TypeInterface::read()
     *
     * @throws \Exception
     */
    public function read(Parser $parser): array
    {
        $results = [];
        $handler = $this->readFxn;

        $varInt = $this->varint->read($parser);

        for ($i = 0; $i < $varInt; $i++) {
            $results[] = $handler($parser);
        }

        return $results;
    }
}
