<?php

namespace alcamo\html_creation\element;

use alcamo\rdfa\MediaType;

/**
 * @brief HTML element \<script>
 *
 * @date Last reviewed 2026-01-21
 */
class Script extends AbstractScriptSupportingElement
{
    public const TAG_NAME = "script";

    /**
     * @param $src `src` attribute.
     *
     * @param $attrs Further attributes. $src overrides `$attrs['src']`.
     *
     * @param $path Local path, defaults to $src without query part.
     */
    public static function newFromLocalUrl(
        string $src,
        ?array $attrs = null,
        $path = null
    ): self {
        /** Call
         *  alcamo::html_creation::element::Link::augmentLocalUrl(). */
        Link::augmentLocalUrl($src, $path);

        /** Determine media type from the file unless the type is set in
         *  $attrs. */
        if (!isset($attrs['type'])) {
            $attrs =
            [ 'type' => MediaType::newFromFilename($path) ] + (array)$attrs;
        }

        return new static(null, [ 'src' => $src ] + (array)$attrs);
    }

    public function __construct($content = null, ?array $attrs = null)
    {
        /**
         * There are browsers which get confused with empty \<script> tags.
         */
        parent::__construct($content ?? '', $attrs);
    }
}
