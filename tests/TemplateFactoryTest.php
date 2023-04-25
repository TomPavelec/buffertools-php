<?php

declare(strict_types=1);

namespace BitWasp\Buffertools\Tests;

use BitWasp\Buffertools\TemplateFactory;
use BitWasp\Buffertools\Types\VarInt;
use BitWasp\Buffertools\Types\VarString;
use BitWasp\Buffertools\Types\Vector;

class TemplateFactoryTest extends BinaryTest
{
    public function getTestVectors(): array
    {
        $vectors = [];

        for ($i = 8; $i <= 256; $i *= 2) {
            foreach (['', 'le'] as $byteOrder) {
                $vectors[] = [
                    'uint' . $i . $byteOrder,
                    '\BitWasp\Buffertools\Types\Uint' . $i,
                ];
                $vectors[] = [
                    'int' . $i . $byteOrder,
                    '\BitWasp\Buffertools\Types\Int' . $i,
                ];
            }
        }

        $vectors[] = [
            'varint',
            VarInt::class,
        ];

        $vectors[] = [
            'varstring',
            VarString::class,
        ];

        return $vectors;
    }


    /**
     * @dataProvider getTestVectors
     */
    public function testTemplateUint(string $function, string $eClass): void
    {
        $factory = new TemplateFactory(null);
        $factory->$function();
        $template = $factory->getTemplate();
        self::assertEquals(1, \count($template));
        $template = $factory->getTemplate()->getItems();
        self::assertInstanceOf($eClass, \reset($template));
    }


    public function testVector(): void
    {
        $factory = new TemplateFactory(null);
        $factory->vector(
            static fn () => null,
        );
        $template = $factory->getTemplate();
        self::assertEquals(1, \count($template));
        $template = $factory->getTemplate()->getItems();
        self::assertInstanceOf(Vector::class, $template[0]);
    }
}
