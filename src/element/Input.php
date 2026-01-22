<?php

namespace alcamo\html_creation\element;

use alcamo\exception\InvalidEnumerator;

/**
 * @brief HTML element \<input>
 *
 * @date Last reviewed 2026-01-21
 */
class Input extends AbstractSpecificElement
{
    public const TAG_NAME = "input";

    /// Valid \<input> types
    public const TYPES = [
        "button",
        "checkbox",
        "color",
        "date",
        "datetime-local",
        "email",
        "file",
        "hidden",
        "image",
        "month",
        "number",
        "password",
        "radio",
        "range",
        "reset",
        "search",
        "submit",
        "tel",
        "text",
        "time",
        "url",
        "week"
    ];

    /**
     * @param $name `name` attribute.
     *
     * @param $value `value` attribute.
     *
     * @param $attrs Further attributes. $name and $value override
     * ``$attrs['name']`` and ``$attrs['value']``.
     */
    public static function newFromNameValue(
        string $name,
        $value,
        ?array $attrs = null
    ): self {
        return new static(compact('name', 'value') + (array)$attrs);
    }

    /**
     * @param $attrs Attributes. If ``$attrs['type']`` is not set and a class
     * constant TYPE is defiend, ``$attrs['type']`` is set to static::TYPE.
     */
    public function __construct(array $attrs)
    {
        parent::__construct(null, $attrs);

        if (isset($attrs['type']) && !in_array($attrs['type'], static::TYPES)) {
            /** @throw alcamo::exception::InvalidEnumerator if the value for
             *  `type` is not a valid type. */
            throw (new InvalidEnumerator())->setMessageContext(
                [
                    'value' => $attrs['type'],
                    'expectedOneOf' => static::TYPES,
                    'extraMessage' => 'not a valid <input> type'
                ]
            );
        }
    }
}
