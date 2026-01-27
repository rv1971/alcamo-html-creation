<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

class LinkTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $href,
        $attrs,
        $expectedString
    ) {
        $link = new Link($href, $attrs);

        $this->assertSame('link', $link->getTagName());

        $this->assertInstanceOf(TokenList::class, $link['class']);

        $this->assertSame($href, $link['href']);

        $this->assertNull($link->getContent());

        $this->assertEquals($expectedString, (string)$link);
    }

    public function basicsProvider()
    {
        return [
            'typical-use' => [
                'foo.css',
                [ 'rel' => 'stylesheet', 'type' => 'text/css' ],
                '<link rel="stylesheet" type="text/css" href="foo.css"/>'
            ],

            'override-attrs' => [
                'baz.php',
                [ 'href' => 'qux.php', 'rel' => 'dc:source' ],
                '<link href="baz.php" rel="dc:source"/>'
            ]
        ];
    }
}
