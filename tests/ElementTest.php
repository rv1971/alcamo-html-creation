<?php

namespace alcamo\html_creation;

use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    /**
     * @dataProvider createAOrSpanProvider
     */
    public function testCreateAOrSpan(
        $content,
        $class,
        $title,
        $href,
        $attrs,
        $expetctedResult
    ): void {
        $this->assertSame(
            $expetctedResult,
            Element::createAOrSpan(
                $content,
                $class,
                $title,
                $href,
                $attrs
            )
        );
    }

    public function createAOrSpanProvider(): array
    {
        return [
            [ 'foo', null, null, null, null, 'foo' ],
            [
                'Lorem ipsum',
                'foo',
                'Lorem ipsum dolor sit amet',
                null,
                [],
                '<span class="foo" title="Lorem ipsum dolor sit amet">Lorem ipsum</span>'
            ],
            [
                'cons',
                'bar',
                null,
                null,
                [ 'xml:id' => 'C', 'class' => 'baz', 'title' => 'consetetur' ],
                '<span xml:id="C" class="bar" title="consetetur">cons</span>'
            ],
            [
                's',
                null,
                'sadipscing',
                'https://www.example.org',
                [ 'xml:id' => 'S' ],
                '<a xml:id="S" title="sadipscing" href="https://www.example.org">s</a>'
            ],
        ];
    }
}
