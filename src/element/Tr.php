<?php

namespace alcamo\html_creation\element;

use alcamo\xml_creation\{Comment, Raw};

/**
 * @brief HTML element \<tr>
 *
 * @date Last reviewed 2026-01-21
 */
class Tr extends AbstractSpecificElement
{
    public const TAG_NAME = "tr";

    /// Default class to wrap cell content into
    public const DEFAULT_CELL_CLASS = Td::class;

    /**
     * @brief Wrap each non-`null` item into a cell unless it is an element
     * allowed within \<tr>.
     *
     * @param $cellClass Class to wrap items into, defaults to
     * alcamo::html_creation::element::Tr::DEFAULT_CELL_CLASS.
     */
    public static function newFromItems(
        iterable $items,
        ?array $attrs = null,
        ?string $cellClass = null
    ) {
        if (!isset($cellClass)) {
            $cellClass = static::DEFAULT_CELL_CLASS;
        }

        $content = [];

        foreach ($items as $item) {
            if (isset($item)) {
                $content[] =
                    ($item instanceof Comment
                     || $item instanceof Raw
                     || $item instanceof AbstractTableCell
                     || $item instanceof AbstractScriptSupportingElement)
                    ? $item
                    : new $cellClass($item);
            }
        }

        return new static($content, $attrs);
    }
}
