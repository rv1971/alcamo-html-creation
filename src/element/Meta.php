<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<meta>
 *
 * @date Last reviewed 2026-01-20
 */
class Meta extends AbstractSpecificElement
{
    public const TAG_NAME = "meta";

    /// \<meta> elements have no content, only attributes
    public function __construct(array $attrs)
    {
        parent::__construct(null, $attrs);
    }
}
