<?php

namespace alcamo\html_creation\element;

use alcamo\xml_creation\Raw;

/**
 * @brief Common base class for Optgroup and Select
 *
 * @date Last reviewed 2026-01-21
 */
abstract class AbstractOptionList extends AbstractSpecificElement
{
    /**
     * @brief Create array of options from a sequence of values
     *
     * @param $values Values to become the option values
     *
     * @param $compareTo Set the attribute `checked` if the value matches
     * $compareTo using
     * alcamo::html_creation::element::AbstractCheckableInput::matches().
     *
     * @param $attrs Further attributes for the options. $name and
     * value override ``$attrs['name']`` and ``$attrs['value']``.
     *
     * Create an Option element for each non-`null` value unless it is an
     * element allowed within \<select>.
     */
    public static function createOptionsFromValues(
        iterable $values,
        $compareTo = null,
        ?array $attrs = null
    ): array {
        $options = [];

        foreach ($values as $value) {
            if (isset($value)) {
                $options[] =
                    ($value instanceof Raw
                     || $value instanceof Option
                     || $value instanceof Optgroup
                     || $value instanceof AbstractScriptSupportingElement)
                    ? $value
                    : new Option($value, null, $compareTo, $attrs);
            }
        }

        return $options;
    }

    /**
     * @brief Create array of options from a map of values to contents
     *
     * @param $map Map of values (which become the option values) to labels
     * (which become the option contents).
     *
     * @param $compareTo Set the attribute `checked` if the value matches
     * $compareTo using
     * alcamo::html_creation::element::AbstractCheckableInput::matches().
     *
     * @param $attrs Further attributes for the options. $name and
     * value override ``$attrs['name']`` and ``$attrs['value']``.
     *
     * Create an Option element for each non-`null` value unless it is an
     * element allowed within \<select>.
     */
    public static function createOptionsFromMap(
        iterable $map,
        $compareTo = null,
        ?array $attrs = null
    ): array {
        $options = [];

        foreach ($map as $value => $content) {
            $options[] =
                ($content instanceof Raw
                 || $content instanceof Option
                 || $content instanceof Optgroup
                 || $content instanceof AbstractScriptSupportingElement)
                ? $content
                : new Option($value, $content, $compareTo, $attrs);
        }

        return $options;
    }
}
