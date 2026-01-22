# Usage example

~~~
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
~~~

This example is contained in this package as a file in the `bin`
directory. It will output

~~~
<!DOCTYPE html>
<html>
<head>
<title>Hello</title>
<style>.bold { font-weight: bold }</style>
</head>
<body>
<p id="hello">Hello, world!</p>
<ul>
<li>foo</li>
<li>bar</li>
<li class="bold">baz</li>
</ul>
</body>
</html>
~~~

# Overview

The package offers one class for each HTML element.

For most of them, the constructor takes two parameters, the element
content and optionally the attributes. (Some elements have different
constructors, in particular elements like `<link>` which do not have a
content).

The content can be:
* An iterable, whose items will be output one after the other.
* An `alcamo\xml_creation\Nodes` object, which will be handled like an
  iterable.
* A stringable, which will be output with the special characters &,<,>
  escaped.
* An `alcamo\xml_creation\Raw` object which will be output unchanged.

Each element automatically has an attribute `class` of type
`alcamo\xml_creation\TokenList` which is empty at the beginning. This
makes it easy to add classes to an element without caring whether it
already has a `class` attribute (and whether is already has the class
to add), as in the above example. Attributes having an empty
`TokenList` are suppressed in the output.

Many classes have additional factory methods to simplify HTML
creation, such as `Ul::newFromItems()` in the above example. These
methods are one of the primary reasons why this package may be useful.

See the doxygen documentation for details.

