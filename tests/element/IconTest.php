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

  /**
   * @dataProvider newFromLocalUrlProvider
   */
    public function testNewFromLocalUrl(
        $href,
        $attrs,
        $path,
        $expectedString
    ) {
        $icon = Icon::newFromLocalUrl($href, $attrs, $path);

        $this->assertSame('link', $icon->getTagName());

        $this->assertInstanceOf(TokenList::class, $icon['class']);

        $this->assertSame($attrs['rel'] ?? 'icon', $icon['rel']);

        $this->assertNull($icon->getContent());

        $this->assertEquals($expectedString, (string)$icon);
    }

    public function newFromLocalUrlProvider()
    {
        $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

        $m16 = gmdate('YmdHis', filemtime("${baseDir}alcamo-16.png"));
        $m32 = gmdate('YmdHis', filemtime("${baseDir}alcamo-32.png"));
        $m64 = gmdate('YmdHis', filemtime("${baseDir}alcamo-64.png"));
        $mJpeg = gmdate('YmdHis', filemtime("${baseDir}alcamo-16.jpeg"));
        $mSvg = gmdate('YmdHis', filemtime("${baseDir}alcamo.svg"));
        $mIco = gmdate('YmdHis', filemtime("${baseDir}alcamo.ico"));

        return [
            'png16' => [
                "${baseDir}alcamo-16.png",
                null,
                null,
                "<link type=\"image/png\" sizes=\"16x16\" href=\"${baseDir}alcamo-16.png?m=$m16\" rel=\"icon\"/>"
            ],

            'png32' => [
                "${baseDir}alcamo-32.png",
                [ 'id' => 'BAZ' ],
                null,
                "<link id=\"BAZ\" type=\"image/png\" sizes=\"32x32\" "
                . "href=\"${baseDir}alcamo-32.png?m=$m32\" rel=\"icon\"/>"
            ],

            'jpeg' => [
                "/icons/alcamo-16.jpeg",
                [ 'rel' => 'apple-touch-icon' ],
                "${baseDir}alcamo-16.jpeg",
                "<link rel=\"apple-touch-icon\" type=\"image/jpeg\" "
                . "sizes=\"16x16\" href=\"/icons/alcamo-16.jpeg?m=$mJpeg\"/>"
            ],

            'svg' => [
                "${baseDir}alcamo.svg",
                null,
                null,
                "<link type=\"image/svg+xml\" sizes=\"any\" "
                . "href=\"${baseDir}alcamo.svg?m=$mSvg\" rel=\"icon\"/>"
            ],

            'ico' => [
                "${baseDir}alcamo.ico",
                null,
                null,
                "<link type=\"image/vnd.microsoft.icon\" sizes=\"64x64\" "
                . "href=\"${baseDir}alcamo.ico?m=$mIco\" rel=\"icon\"/>"
            ],

            'explicit-type' => [
                "${baseDir}alcamo-64.png",
                [ 'type' => 'image/x-foo' ],
                null,
                "<link type=\"image/x-foo\" sizes=\"64x64\" "
                . "href=\"${baseDir}alcamo-64.png?m=$m64\" rel=\"icon\"/>"
            ]
        ];
    }
}
