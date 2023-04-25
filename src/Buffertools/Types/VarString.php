<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Types;

use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Exceptions\ParserOutOfRange;
use BitWasp\Buffertools\Parser;

class VarString extends AbstractType
{
    public function __construct(private VarInt $varint)
    {
        parent::__construct($varint->getByteOrder());
    }


    /**
     * {@inheritDoc}
     *
     * @see TypeInterface::write()
     */
    public function write(mixed $value): string
    {
        if (!$value instanceof BufferInterface) {
            throw new \InvalidArgumentException('Must provide a buffer');
        }

        return $this->varint->write($value->getSize()) . $value->getBinary();
    }


    /**
     * {@inheritDoc}
     *
     * @see TypeInterface::write()
     *
     * @throws ParserOutOfRange
     * @throws \Exception
     */
    public function read(Parser $parser): BufferInterface
    {
        $length = $this->varint->read($parser);

        if ($length > $parser->getSize() - $parser->getPosition()) {
            throw new ParserOutOfRange("Insufficient data remaining for VarString");
        }

        if (\gmp_cmp(\gmp_init($length, 10), \gmp_init(0, 10)) === 0) {
            return new Buffer;
        }

        return $parser->readBytes((int) $length);
    }
}
