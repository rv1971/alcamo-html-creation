<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<option>
 *
 * @date Last reviewed 2026-01-21
 */
class Option extends AbstractSpecificElement
{
    public const TAG_NAME = "option";

    /**
     * @param $value `value` attribute.
     *
     * @param $content `content` attribute. Defaults to $value.
     *
     * @param $compareTo Set the attribute `checked` if $value matches
     * $compareTo using
     * alcamo::html_creation::element::AbstractCheckableInput::matches().
     *
     * Also set the attribute `checked` if $compareTo is `null` and value is
     * the empty string. This allows to automatically select a default entry
     * (which may mean "all" or "none") if nothing has been explicitely
     * selected.
     *
     * @param $attrs Further attributes. $content and $name override
     * `$attrs['content']` and `$attrs['name']`.
     */
    public function __construct(
        $value,
        $content = null,
        $compareTo = null,
        ?array $attrs = null
    ) {
      /* If $content is unset, $value will be used as content, and in this
       * case the `value` attribute is redundant. */
        $attrs = isset($content)
            ? compact('value') + (array)$attrs
            : (array)$attrs;

        if (isset($compareTo)) {
            $attrs['selected'] =
                AbstractCheckableInput::matches($value, $compareTo);
        } elseif ($value === '') {
            $attrs['selected'] = true;
        }

        parent::__construct($content ?? $value, $attrs);
    }
}
