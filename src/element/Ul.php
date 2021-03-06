<?php

namespace alcamo\html_creation\element;

use alcamo\xml_creation\Raw;

/**
 * @brief HTML element \<ul>
 *
 * @date Last reviewed 2021-06-15
 */
class Ul extends AbstractSpecificElement
{
    public const TAG_NAME = "ul";

    /**
     * @brief Wrap each non-`null` item into an Li unless it is an element
     * allowed within @ref TAG_NAME.
     */
    public function __construct(iterable $items, ?iterable $attrs = null)
    {
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

        parent::__construct($content, $attrs);
    }
}
