<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

class IconTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $href,
        $attrs,
        $expectedString
    ) {
        $icon = new Icon($href, $attrs);

        $this->assertSame('link', $icon->getTagName());

        $this->assertInstanceOf(TokenList::class, $icon['class']);

        $this->assertSame('icon', $icon['rel']);

        $this->assertSame($href, $icon['href']);

        $this->assertNull($icon->getContent());

        $this->assertEquals($expectedString, (string)$icon);
    }

    public function basicsProvider()
    {
        return [
            'typical-use' => [
                'quux.png',
                [ 'type' => 'image/png' ],
                '<link type="image/png" href="quux.png" rel="icon"/>'
            ]
        ];
    }
}
