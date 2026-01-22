<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<link> referring to a stylesheet
 *
 * @date Last reviewed 2026-01-21
 */
class Stylesheet extends Link
{
    public const DEFAULT_ATTRS = [ 'rel' => 'stylesheet' ];

    /**
     * @param $href `href` attribute.
     *
     * @param $attrs Further attributes. $href overrides `$attrs['href']`.
     *
     * @param $path Local path, defaults to $href without query part.
     *
     * Unlike alcamo::xml_creation::Link::newFromLocalUrl(), this method does
     * not create a `type` attribute.
     */
    public static function newFromLocalUrl(
        string $href,
        ?array $attrs = null,
        $path = null
    ): Link {
        /** Call augmentLocalUrl(). */
        static::augmentLocalUrl($href, $path);

        return new static($href, $attrs);
    }
}
