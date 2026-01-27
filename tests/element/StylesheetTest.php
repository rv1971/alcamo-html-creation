<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

class StylesheetTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $href,
        $attrs,
        $expectedString
    ) {
        $stylesheet = new Stylesheet($href, $attrs);

        $this->assertSame('link', $stylesheet->getTagName());

        $this->assertInstanceOf(TokenList::class, $stylesheet['class']);

        $this->assertSame('stylesheet', $stylesheet['rel']);

        $this->assertSame($href, $stylesheet['href']);

        $this->assertNull($stylesheet->getContent());

        $this->assertEquals($expectedString, (string)$stylesheet);
    }

    public function basicsProvider()
    {
        return [
            'typical-use' => [
                'foo.css',
                null,
                '<link href="foo.css" rel="stylesheet"/>'
            ]
        ];
    }
}
