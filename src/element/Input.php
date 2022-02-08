<?php

namespace alcamo\html_creation\element;

use alcamo\exception\InvalidEnumerator;

/**
 * @brief HTML element \<input>
 *
 * Derived classes my define a class constant TYPE which becomes the default
 * value for the `type` attribute.
 *
 * @date Last reviewed 2021-06-16
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
     * @param $attrs Further attributes. If ``$attrs['type']`` is not set and
     * a class constant TYPE is defiend, ``$attrs['type']`` is set to
     * static::TYPE. $name and $value override ``$attrs['name']`` and
     * ``$attrs['value']``.
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
        $attrs = (array)$attrs;

        if (!isset($attrs['type']) && defined('static::TYPE')) {
            $attrs['type'] = static::TYPE;
        }

        if (!in_array($attrs['type'], static::TYPES)) {
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

        parent::__construct(null, $attrs);
    }
}
