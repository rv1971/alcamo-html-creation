<?php

namespace alcamo\html_creation\element;

use alcamo\xml_creation\{Comment, Raw};

/**
 * @brief Base class for elements that may have \<tr> children
 *
 * @date Last reviewed 2026-01-21
 */
abstract class AbstractRowgroupElement extends AbstractSpecificElement
{
    /// Default class to wrap cell content into
    public const DEFAULT_CELL_CLASS = Td::class;

    /**
     * @brief Create an object that contains one Tr item
     *
     * @params $items Items that are wrapped into cells if needed (and if
     * non-`null`), using DEFAULT_CELL_CLASS.
     *
     * @param $attrs Attributes to apply to this element (not to the \<tr>
     * element).
     */
    public static function newFromCellsIterable(
        iterable $items,
        ?array $attrs = null
    ): self {
        return new static(
            Tr::newFromItems($items, null, static::DEFAULT_CELL_CLASS),
            $attrs
        );
    }

    /**
     * @brief Create an object that contains an array of Tr items
     *
     * @params $rows Rows that are wrapped item into Tr elements if needed
     * (and if non-`null`), using DEFAULT_CELL_CLASS.
     *
     * @param $attrs Attributes to apply to this element (not to the \<tr>
     * element).
     */
    public static function newFromRowsIterable(
        iterable $rows,
        ?array $attrs = null
    ): self {
        $content = [];

        foreach ($rows as $row) {
            if (isset($row)) {
                $content[] =
                    ($row instanceof Comment
                     || $row instanceof Raw
                     || $row instanceof Tr
                     || $row instanceof AbstractScriptSupportingElement)
                    ? $row
                    : Tr::newFromItems($row, null, static::DEFAULT_CELL_CLASS);
            }
        }

        return new static($content, $attrs);
    }
}
