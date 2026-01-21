<?php

namespace alcamo\html_creation\element;

class Select extends AbstractOptionList
{
    public const TAG_NAME = "select";

    /**
     * @brief Create from a sequence of values
     *
     * @param @name Value for `name` attribute
     *
     * @param $values Values to become the option values
     *
     * @param $compareTo Set the attribute `checked` if the value matches
     * $compareTo using
     * alcamo::html_creation::element::AbstractCheckableInput::matches().
     *
     * @param $attrs Further attributes of the \<select> element. $name
     * overrides `$attrs['name']`.
     */
    public static function newFromValueSequence(
        $name,
        iterable $values,
        $compareTo = null,
        ?array $attrs = null
    ) {
        return new static(
            self::createOptionsFromValues($values, $compareTo),
            compact('name') + (array)$attrs
        );
    }

    /**
     * @brief Create from map of values to contents
     *
     * @param @name Value for `name` attribute
     *
     * @param $map Map of values (which become the option values) to labels
     * (which become the option contents).
     *
     * @param $compareTo Set the attribute `checked` if the value matches
     * $compareTo using
     * alcamo::html_creation::element::AbstractCheckableInput::matches().
     *
     * @param $attrs Further attributes of the \<select> element. $name
     * overrides `$attrs['name']`.
     */
    public static function newFromMap(
        $name,
        iterable $values,
        $compareTo = null,
        ?array $attrs = null
    ) {
        return new static(
            self::createOptionsFromMap($values, $compareTo),
            compact('name') + (array)$attrs
        );
    }

    /**
     * If $name ends with `[]`, the attribute `multiple` is automatically set.
     */
    public function __construct($content, ?array $attrs = null)
    {
        if (
            isset($attrs)
                && isset($attrs['name'])
                && substr($attrs['name'], -2) == '[]'
        ) {
            $attrs['multiple'] = true;
        }

        parent::__construct($content, $attrs);
    }
}
