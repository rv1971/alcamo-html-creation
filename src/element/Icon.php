<?php

namespace alcamo\html_creation\element;

use alcamo\rdfa\MediaType;

/**
 * @brief HTML element \<link> referring to an icon
 *
 * @date Last reviewed 2026-01-21
 */
class Icon extends Link
{
    public const DEFAULT_ATTRS = [ 'rel' => 'icon' ];

    /**
     * @param $href `href` attribute.
     *
     * @param $attrs Further attributes. $href overrides `$attrs['href']`.
     *
     * @param $path Local path, defaults to $href without query part.
     */
    public static function newFromLocalUrl(
        string $href,
        ?array $attrs = null,
        $path = null
    ): Link {
        /** Call
         *  alcamo::html_creation::element::Link::augmentLocalUrl(). */
        static::augmentLocalUrl($href, $path);

        /** Determine media type from the file unless the type is set in
         *  $attrs. */
        if (isset($attrs['type'])) {
            $type = $attrs['type'] instanceof MediaType
                ? $attrs['type']
                : MediaType::newFromString($attrs['type']);
        } else {
            $type = MediaType::newFromFilename($path);

            $attrs['type'] = $type;
        }

        /** Determine size information from the file unless the type is set in
         *  $attrs. */
        if (!isset($attrs['sizes']) && $type->getType() == 'image') {
            if ($type->getSubtype() == 'svg+xml') {
                $attrs['sizes'] = 'any';
            } else {
                $a = getimagesize($path);

                if ($a !== false) {
                    $attrs['sizes'] = "{$a[0]}x{$a[1]}";
                }
            }
        }

        return new static($href, $attrs);
    }
}
