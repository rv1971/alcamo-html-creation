<?php
use alcamo\html_creation\element\{
    Body,
    Head,
    Html,
    Li,
    P,
    Style,
    Title,
    Ul
};

use alcamo\xml_creation\{DoctypeDecl, Nodes};

include $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

$baz = new Li('baz');

$baz['class']->add('bold');

$html = new Nodes (
    new DoctypeDecl('html'),
    new Html(
        [
            new Head(
                [
                    new Title('Hello'),
                    new Style('.bold { font-weight: bold }')
                ]
            ),
            new Body(
                [
                    new P('Hello, world!', [ 'id' => 'hello' ] ),
                    Ul::newFromItems(
                        [
                            'foo',
                            'bar',
                            $baz
                        ]
                    )
                ]
            )
        ]
    )
);

Nodes::setFormatOutput(true);

echo $html . PHP_EOL;
