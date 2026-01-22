<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<a>
 *
 * @date Last reviewed 2026-01-20
 */
class A extends AbstractSpecificElement
{
    public const TAG_NAME = "a";

    /**
     * @param $href `href` attribute.
     *
     * @param $content Content, defaults to $href.
     *
     * @param $attrs Further attributes. $href overrides `$attrs['href']`.
     */
    public static function newFromUrl(
        $href,
        $content = null,
        ?array $attrs = null
    ): self {
        if (isset($href)) {
            $attrs['href'] = $href;
        }

        return new static($content ?? $href, $attrs);
    }
}
