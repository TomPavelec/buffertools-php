<?php

declare(strict_types=1);

namespace BitWasp\Buffertools;

interface BufferInterface
{
    /**
     * @return BufferInterface
     *
     * @throws \Exception
     */
    public function slice(int $start, ?int $end = null): self;


    /**
     * Get the size of the buffer to be returned
     */
    public function getSize(): int;


    /**
     * Get the size of the value stored in the buffer
     */
    public function getInternalSize(): int;


    public function getBinary(): string;


    public function getHex(): string;


    public function getInt(): int|string;


    public function getGmp(): \GMP;


    /**
     * @return Buffer
     */
    public function flip(): self;


    /**
     * @param BufferInterface $other
     */
    public function equals(self $other): bool;
}
