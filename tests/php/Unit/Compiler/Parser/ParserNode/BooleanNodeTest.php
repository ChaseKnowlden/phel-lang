<?php

declare(strict_types=1);

namespace PhelTest\Unit\Compiler\Parser\ParserNode;

use Phel\Compiler\Parser\ParserNode\BooleanNode;
use Phel\Lang\SourceLocation;
use PHPUnit\Framework\TestCase;

final class BooleanNodeTest extends TestCase
{
    public function testGetCode(): void
    {
        self::assertEquals(
            'true',
            (new BooleanNode('true', $this->loc(1, 0), $this->loc(1, 4), true))->getCode()
        );
    }

    public function testGetStartLocation(): void
    {
        self::assertEquals(
            $this->loc(1, 0),
            (new BooleanNode('true', $this->loc(1, 0), $this->loc(1, 4), true))->getStartLocation()
        );
    }

    public function testGetEndLocation(): void
    {
        self::assertEquals(
            $this->loc(1, 4),
            (new BooleanNode('true', $this->loc(1, 0), $this->loc(1, 4), true))->getEndLocation()
        );
    }

    public function testValue(): void
    {
        self::assertTrue(
            (new BooleanNode('true', $this->loc(1, 0), $this->loc(1, 4), true))->getValue()
        );
    }

    private function loc(int $line, int $column): SourceLocation
    {
        return new SourceLocation('string', $line, $column);
    }
}
