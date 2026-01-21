<?php

namespace alcamo\html_creation\element;

/**
 * @brief Common parent of checkboxes and radio buttons
 *
 * @date Last reviewed 2026-01-20
 */
abstract class AbstractCheckableInput extends Input
{
    /**
     * Return `true` if $value matches $compareTo. This is tested as follows:
     * - Call `$compareTo->contains($value)` if this method is available.
     * - Else, if $compareTo is an array, check whether is contains $value.
     * - Else, check whether $value is equal to $compareTo using the ==
     * operator.
     */
    public static function matches($value, $compareTo): bool
    {
        switch (true) {
            case is_callable([ $compareTo, 'contains' ]):
                return $compareTo->contains($value);

            case is_array($compareTo):
                return in_array($value, $compareTo);

            default:
                return $value == $compareTo;
        }
    }

    /**
     * @param $name `name` attribute.
     *
     * @param $value `value` attribute.
     *
     * @param $compareTo Set the attribute `checked` if $value matches
     * $compareTo using
     * alcamo::html_creation::element::AbstractCheckableInput::matches().
     *
     * @param $attrs Further attributes. $name and $value override
     * ``$attrs['name']`` and ``$attrs['value']``.
     */
    public static function newFromNameValueCompare(
        $name,
        $value,
        $compareTo = null,
        ?array $attrs = null
    ): self {
        $attrs = compact('name', 'value') + (array)$attrs;

        if (isset($compareTo)) {
            $attrs['checked'] = static::matches($value, $compareTo);
        }

        return new static($attrs);
    }
}
