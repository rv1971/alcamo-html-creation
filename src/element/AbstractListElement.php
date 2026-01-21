<?php

namespace alcamo\html_creation\element;

use alcamo\xml_creation\Raw;

/**
 * @brief Common parent of HTML elements \<ol> and \<ul>
 *
 * @date Last reviewed 2026-01-20
 */
abstract class AbstractListElement extends AbstractSpecificElement
{
    /**
     * @brief Wrap each non-`null` item into an Li unless it is an element
     * allowed within this element.
     */
    public static function newFromItems(
        iterable $items,
        ?array $attrs = null
    ): self {
        $content = [];

        foreach ($items as $item) {
            if (isset($item)) {
                $content[] =
                    ($item instanceof Raw
                     || $item instanceof Li
                     || $item instanceof AbstractScriptSupportingElement)
                    ? $item
                    : new Li($item);
            }
        }

        return new static($content, $attrs);
    }
}
