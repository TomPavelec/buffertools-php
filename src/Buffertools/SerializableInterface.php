<?php

declare(strict_types=1);

namespace BitWasp\Buffertools;

interface SerializableInterface
{
    public function getBuffer(): BufferInterface;
}
