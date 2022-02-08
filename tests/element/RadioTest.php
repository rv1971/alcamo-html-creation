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
                '<input name="foo" value="bar" id="BAR" type="radio"/>'
            ],

            'unchecked' => [
                'foo',
                'bar',
                'barr',
                [ 'id' => 'BAR' ],
                '<input name="foo" value="bar" id="BAR" type="radio"/>'
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
   * @dataProvider newFromValueSequenceProvider
   */
    public function testCreateLabeledRadiosFromValueSequence(
        $name,
        $values,
        $compareTo,
        $attrs,
        $expectedString
    ) {
        $radios = Radio::createLabeledRadiosFromValueSequence(
            $name,
            $values,
            $compareTo,
            $attrs
        );

        $this->assertEquals($expectedString, (string)new Nodes($radios));
    }

    public function newFromValueSequenceProvider()
    {
        return [
            [
                'foo',
                [ 'bar', null, 'baz', 'qux' ],
                'baz',
                [ 'class' => 'corge' ],
                '<label><input name="foo" value="bar" class="corge" type="radio"/>bar</label>'
                . '<label><input name="foo" value="baz" class="corge" checked="checked" type="radio"/>baz</label>'
                . '<label><input name="foo" value="qux" class="corge" type="radio"/>qux</label>'
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
                '<label><input name="x" value="l" class="loremipsum" checked="checked" type="radio"/>Lorem</label>'
                . '<label><input name="x" value="i" class="loremipsum" type="radio"/>ipsum</label>'
            ]
        ];
    }
}
