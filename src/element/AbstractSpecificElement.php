<?php

namespace alcamo\html_creation\element;

use alcamo\html_creation\Element;

/**
 * @brief Base class for HTML element classes for specific tag names
 *
 * @attention Derived classes must redefine the public constant TAG_NAME.
 *
 * @attention Note that the order of parameters is first content, then
 * attributes, unlike alcamo::html_creation::Element, because it happens very
 * frequently that HTML elements have a content but no attributes.
 *
 * @date Last reviewed 2026-01-20
 */
abstract class AbstractSpecificElement extends Element
{
    /// Tag name for elements of this class
    public const TAG_NAME = '';

    /// Default attributes to add if not defined
    public const DEFAULT_ATTRS = [];

    public function __construct($content = null, ?array $attrs = null)
    {
        parent::__construct(
            static::TAG_NAME,
            (array)$attrs + static::DEFAULT_ATTRS,
            $content
        );
    }
}
