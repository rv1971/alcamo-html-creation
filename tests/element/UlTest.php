<?php

namespace alcamo\html_creation\element;

use Ds\Map;
use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class UlTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $content,
        $attrs,
        $expectedString
    ): void {
        $ul = Ul::newFromItems($content, $attrs);

        $this->assertSame('ul', $ul->getTagName());

        $this->assertInstanceOf(TokenList::class, $ul['class']);

        $this->assertEquals($expectedString, (string)$ul);
    }

    public function basicsProvider(): array
    {
        return [
            'empty' => [ [], null, '<ul></ul>' ],

            'array' => [
                [ 'FOO', new Li(42), new B('BAR'), null ],
                null,
                '<ul><li>FOO</li><li>42</li><li><b>BAR</b></li></ul>',
            ],

            'Map' => [
                new Map([
                    43,
                    new Script(null, [ 'src' => 'foo.js' ]),
                    new Template('Lorem ipsum'),
                    new Li('baz', [ 'id' => 'qux' ])
                ]),
                [ 'class' => 'important' ],
                '<ul class="important"><li>43</li><script src="foo.js"></script>'
                . '<template>Lorem ipsum</template><li id="qux">baz</li></ul>',
            ],
        ];
    }
}
