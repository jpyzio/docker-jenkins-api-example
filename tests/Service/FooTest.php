<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Foo;
use PHPUnit\Framework\TestCase;

class FooTest extends TestCase
{
    public function testGetFoo(): void
    {
        $foo = new Foo();
        self::assertSame('Foo', $foo->getFoo());
    }
}