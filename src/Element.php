<?php

namespace alcamo\html_creation;

use alcamo\html_creation\element\{A, Span};
use alcamo\xml_creation\Element as XmlElement;
use alcamo\xml_creation\TokenList;

/**
 * @namespace alcamo::html_creation
 *
 * @brief Simple classes to create HTML code without need for a factory
 */

/**
 * @brief HTML element that can be serialized to HTML text
 *
 * @date Last reviewed 2026-01-20
 */
class Element extends XmlElement
{
    /// @copydoc alcamo::xml_creation::Element::ATTR_CLASS
    public const ATTR_CLASS = Attr::class;

    /**
     * @brief Create appropriate HTML element or bare content
     *
     * @param $content (visible) content of the element to create.
     *
     * @param $class Value for the `class` attribute.
     *
     * @param $title Value for the `title` attribute.
     *
     * @param $title Value for the `href` attribute.
     *
     * @param $attrs any other attributes. The above parameters override
     * attributes given here.
     *
     * @return
     * - `<a>`, if $href is set
     * - `<span>`, if $href is not set but there are attributes
     * - $content unchanged, otherwise
     */
    public static function createAOrSpan(
        string $content,
        ?string $class = null,
        ?string $title = null,
        ?string $href = null,
        ?array $attrs = null
    ): string {
        $attrs = (array)$attrs;

        if (isset($class)) {
            $attrs['class'] = $class;
        }

        if (isset($title)) {
            $attrs['title'] = $title;
        }

        if (isset($href)) {
            $attrs['href'] = $href;
        }

        switch (true) {
            case isset($href):
                return new A($content, $attrs);

            case $attrs:
                return new Span($content, $attrs);

            default:
                return $content;
        }
    }

    /// Call XmlElement::__construct(), then sanitizeAttrs()
    public function __construct(
        string $tagName,
        ?iterable $attrs = null,
        $content = null
    ) {
        parent::__construct($tagName, $attrs, $content);

        $this->sanitizeAttrs();
    }

    /**
     * @brief Ensure the `class` attribute is always present and is a
     * TokenList
     *
     * This makes it much easier to add classes. If the TokenList remains
     * empty, the attribute will be omitted upon serialization.
     */
    protected function sanitizeAttrs()
    {
        if (!isset($this->data_['class'])) {
            $this->data_['class'] = new TokenList();
        } elseif (!($this->data_['class'] instanceof TokenList)) {
            $this->data_['class'] = new TokenList($this->data_['class']);
        }
    }
}
