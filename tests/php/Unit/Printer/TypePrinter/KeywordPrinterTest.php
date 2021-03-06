<?php

declare(strict_types=1);

namespace PhelTest\Unit\Printer\TypePrinter;

use Generator;
use Phel\Lang\Keyword;
use Phel\Printer\TypePrinter\KeywordPrinter;
use PHPUnit\Framework\TestCase;

final class KeywordPrinterTest extends TestCase
{
    /**
     * @dataProvider printerDataProvider
     */
    public function testPrint(string $expected, Keyword $keyword): void
    {
        self::assertSame(
            $expected,
            (new KeywordPrinter())->print($keyword)
        );
    }

    public function printerDataProvider(): Generator
    {
        yield 'string name' => [
            'expected' => ':name',
            'keyboard' => new Keyword('name'),
        ];

        yield 'special chars string' => [
            'expected' => ':\\?#__\|\/',
            'keyboard' => new Keyword('\\?#__\|\/'),
        ];
    }
}
