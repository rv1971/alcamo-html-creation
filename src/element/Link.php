<?php

namespace alcamo\html_creation\element;

use alcamo\exception\FileNotFound;
use alcamo\rdfa\MediaType;

/**
 * @brief HTML element \<link>
 *
 * Derived classes my define a class constant REL which becomes the default
 * value for the `rel` attribute.
 *
 * @date Last reviewed 2026-01-27
 */
class Link extends AbstractSpecificElement
{
    public const TAG_NAME = "link";

    /**
     * @param $href `href` attribute.
     *
     * @param $attrs Further attributes. If $href is set, it overrides
     * `$attrs['href']`.
     */
    public function __construct(?string $href, ?array $attrs = null)
    {
        if (isset($href)) {
            $attrs['href'] = $href;
        }

        return parent::__construct(null, $attrs);
    }
}
