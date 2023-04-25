<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Tests;

use BitWasp\Buffertools\CachingTypeFactory;
use BitWasp\Buffertools\TypeFactory;
use BitWasp\Buffertools\TypeFactoryInterface;
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

class TypeFactoryTest extends BinaryTest
{
    public function getTypeVectors(): array
    {
        $vectors = [];
        $addPlainAndLe = static function ($fxnName, $class, array $params = []) use (&$vectors): void {
            $vectors[] = [$fxnName, $class, $params];
            $vectors[] = [$fxnName . 'le', $class, $params];
        };

        $addPlainAndLe('uint8', Uint8::class);
        $addPlainAndLe('uint16', Uint16::class);
        $addPlainAndLe('uint32', Uint32::class);
        $addPlainAndLe('uint64', Uint64::class);
        $addPlainAndLe('uint128', Uint128::class);
        $addPlainAndLe('uint256', Uint256::class);
        $addPlainAndLe('int8', Int8::class);
        $addPlainAndLe('int16', Int16::class);
        $addPlainAndLe('int32', Int32::class);
        $addPlainAndLe('int64', Int64::class);
        $addPlainAndLe('int128', Int128::class);
        $addPlainAndLe('int256', Int256::class);

        return $vectors;
    }


    public function getTypeFactoryVectors(): array
    {
        $vectors = [];

        foreach ([new TypeFactory, new CachingTypeFactory] as $factory) {
            foreach ($this->getTypeVectors() as $vector) {
                $vectors[] = \array_merge([$factory], $vector);
            }
        }

        return $vectors;
    }


    /**
     * @dataProvider getTypeFactoryVectors
     */
    public function testTypeFactory(
        TypeFactoryInterface $factory,
        string $function,
        string $expectedClass,
        array $params = [],
    ): void
    {
        self::assertInstanceOf($expectedClass, $factory->{$function}($params));
    }
}
