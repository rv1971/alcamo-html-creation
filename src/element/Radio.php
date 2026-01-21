<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<input> of type `radio`
 *
 * @date Last reviewed 2026-01-21
 */
class Radio extends AbstractCheckableInput
{
    public const DEFAULT_ATTRS = [ 'type' => 'radio' ];

    /**
     * @brief Create an array of Label objects containing each a radio button
     * and a text label, from a sequence of values
     *
     * @param $name `name` attribute for each radio button.
     *
     * @param $values Values to become the `value` attributes of the radio
     * buttons and the corresponding text label.
     *
     * @param $compareTo Set the attribute `checked` if the value matches
     * $compareTo using
     * alcamo::html_creation::element::AbstractCheckableInput::matches().
     *
     * @param $attrs Further attributes for the radio buttons. $name and
     * value override ``$attrs['name']`` and ``$attrs['value']``.
     *
     * @return array indexed by values.
     */
    public static function createLabeledRadiosFromValues(
        string $name,
        iterable $values,
        $compareTo = null,
        ?array $attrs = null
    ): array {
        $radios = [];

        foreach ($values as $value) {
            if (isset($value)) {
                $radios[(string)$value] = new Label(
                    [
                        static::newFromNameValueCompare(
                            $name,
                            (string)$value,
                            $compareTo,
                            $attrs
                        ),
                        $value
                    ]
                );
            }
        }

        return $radios;
    }

    /**
     * @brief Create an array of Label objects containing each a radio button
     * and a text label, from a map of values to labels
     *
     * @param $name `name` attribute for each radio button.
     *
     * @param $map Map of values (which become the `value` attributes of the
     * radio buttons) to labels (which become the corresponding text labels).
     *
     * @param $compareTo Set the attribute `checked` if the value matches
     * $compareTo using
     * alcamo::html_creation::element::AbstractCheckableInput::matches().
     *
     * @param $attrs Further attributes for the radio buttons. $name and
     * value override ``$attrs['name']`` and ``$attrs['value']``.
     *
     * @return array indexed by values.
     */
    public static function createLabeledRadiosFromMap(
        string $name,
        iterable $map,
        $compareTo = null,
        ?array $attrs = null
    ): array {
        $radios = [];

        foreach ($map as $value => $label) {
            $radios[$value] = new Label(
                [
                    static::newFromNameValueCompare(
                        $name,
                        $value,
                        $compareTo,
                        $attrs
                    ),
                    $label
                ]
            );
        }

        return $radios;
    }
}
