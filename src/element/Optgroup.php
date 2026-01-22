<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<optgroup>
 *
 * @date Last reviewed 2026-01-21
 */
class Optgroup extends AbstractOptionList
{
    public const TAG_NAME = "optgroup";

    /**
     * @brief Create from a sequence of values
     *
     * @param $label Group label
     *
     * @param $values Values to become the option values
     *
     * @param $compareTo Set the attribute `checked` if the value matches
     * $compareTo using
     * alcamo::html_creation::element::AbstractCheckableInput::matches().
     *
     * @param $attrs Further attributes of the \<optgroup> element.
     */
    public static function newFromValues(
        $label,
        iterable $values,
        $compareTo = null,
        ?array $attrs = null
    ) {
        if (isset($label)) {
            $attrs['label'] = $label;
        }

        return new static(
            static::createOptionsFromValues($values, $compareTo),
            $attrs
        );
    }

    /**
     * @brief Create from map of values to contents
     *
     * @param $label Group label
     *
     * @param $map Map of values (which become the option values) to labels
     * (which become the option contents).
     *
     * @param $compareTo Set the attribute `checked` if the value matches
     * $compareTo using
     * alcamo::html_creation::element::AbstractCheckableInput::matches().
     *
     * @param $attrs Further attributes of the \<optgroup> element.
     */
    public static function newFromMap(
        $label,
        iterable $map,
        $compareTo = null,
        ?array $attrs = null
    ) {
        if (isset($label)) {
            $attrs['label'] = $label;
        }

        return new static(self::createOptionsFromMap($map, $compareTo), $attrs);
    }
}
