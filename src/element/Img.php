<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<img>
 *
 * @date Last reviewed 2026-01-27
 */
class Img extends AbstractSpecificElement
{
    public const TAG_NAME = "img";

    /**
     * @param $src `src` attribute.
     *
     * @param $alt `alt` attribute.
     *
     * @param $attrs Further attributes. $src and $alt override
     * `$attrs['src']` and `$attrs['alt']`.
     */
    public function __construct($src, $alt, ?array $attrs = null)
    {
        if (isset($src)) {
            $attrs['src'] = $src;
        }

        if (isset($alt)) {
            $attrs['alt'] = $alt;
        }

        parent::__construct(null, $attrs);
    }
}
