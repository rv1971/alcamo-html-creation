<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<img>
 *
 * @date Last reviewed 2026-01-21
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
     *
     * @param $path Local path, defaults to $src without query part.
     */
    public static function newFromLocalUrl(
        $src,
        $alt,
        ?array $attrs = null,
        $path = null
    ): self {
        Link::augmentLocalUrl($src, $path);

        /** Determine size information from the file unless the type is set in
         *  $attrs. */

        if (!isset($attrs['width']) || !isset($attrs['height'])) {
            $a = getimagesize($path);

            if ($a !== false) {
                $attrs = (array)$attrs
                    + [ 'width' => $a[0], 'height' => $a[1] ];
            }
        }

        return new static($src, $alt, $attrs);
    }

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
        parent::__construct(null, compact('src', 'alt') + (array)$attrs);
    }
}
