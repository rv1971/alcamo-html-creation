<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\{Nodes, TokenList};

class RadioTest extends TestCase
{
  /**
   * @dataProvider newFromNameValueCompareProvider
   */
    public function testNewFromNameValueCompare(
        $name,
        $value,
        $compareTo,
        $attrs,
        $expectedString
    ) {
        $radio =
            Radio::newFromNameValueCompare($name, $value, $compareTo, $attrs);

        $this->assertSame('input', $radio->getTagName());

        $this->assertSame('radio', $radio['type']);

        $this->assertInstanceOf(TokenList::class, $radio['class']);

        if (isset($compareTo)) {
            $this->assertSame($value == $compareTo, $radio['checked']);
        }

        $this->assertEquals($expectedString, (string)$radio);
    }

    public function newFromNameValueCompareProvider()
    {
        return [
            'no-comparison' => [
                'foo',
                'bar',
                null,
                [ 'id' => 'BAR' ],
                '<input id="BAR" name="foo" value="bar" type="radio"/>'
            ],

            'unchecked' => [
                'foo',
                'bar',
                'barr',
                [ 'id' => 'BAR' ],
                '<input id="BAR" name="foo" value="bar" type="radio"/>'
            ],

            'checked' => [
                'baz',
                42,
                42,
                null,
                '<input name="baz" value="42" checked="checked" type="radio"/>'
            ]
        ];
    }

  /**
   * @dataProvider newFromValuesProvider
   */
    public function testCreateLabeledRadiosFromValues(
        $name,
        $values,
        $compareTo,
        $attrs,
        $expectedString
    ) {
        $radios = Radio::createLabeledRadiosFromValues(
            $name,
            $values,
            $compareTo,
            $attrs
        );

        $this->assertEquals($expectedString, (string)new Nodes($radios));
    }

    public function newFromValuesProvider()
    {
        return [
            [
                'foo',
                [ 'bar', null, 'baz', 'qux' ],
                'baz',
                [ 'class' => 'corge' ],
                '<label><input class="corge" name="foo" value="bar" type="radio"/>bar</label>'
                . '<label><input class="corge" name="foo" value="baz" checked="checked" type="radio"/>baz</label>'
                . '<label><input class="corge" name="foo" value="qux" type="radio"/>qux</label>'
            ]
        ];
    }

  /**
   * @dataProvider newFromMapProvider
   */
    public function testCreateLabeledRadiosFromMap(
        $name,
        $values,
        $compareTo,
        $attrs,
        $expectedString
    ) {
        $radios = Radio::createLabeledRadiosFromMap(
            $name,
            $values,
            $compareTo,
            $attrs
        );

        $this->assertEquals($expectedString, (string)new Nodes($radios));
    }

    public function newFromMapProvider()
    {
        return [
            [
                'x',
                [ 'l' => 'Lorem', 'i' => 'ipsum' ],
                'l',
                [ 'class' => 'loremipsum' ],
                '<label><input class="loremipsum" name="x" value="l" checked="checked" type="radio"/>Lorem</label>'
                . '<label><input class="loremipsum" name="x" value="i" type="radio"/>ipsum</label>'
            ]
        ];
    }
}
