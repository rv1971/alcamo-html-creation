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
 * @date Last reviewed 2026-01-21
 */
class Link extends AbstractSpecificElement
{
    public const TAG_NAME = "link";

    /**
     * @param $href string Local URL, potentially with a query part.
     *
     * @param $path Local path.
     *
     * @return URL enriched with a modification date parameter.
     */
    public static function augmentLocalUrl(
        string &$href,
        ?string &$path = null
    ): void {
        $a = explode('?', $href, 2);

        /** If $path is not set, set it to $href without query part. */
        if (!isset($path)) {
            $path = $a[0];
        }

        if (!is_readable($path)) {
            /** @throw alcamo::exception::FileNotFound if $path is not
             *  readable. */
            throw (new FileNotFound())
                ->setMessageContext([ 'filename' => $path ]);
        }

        /** Append modification timestamp if not yet present in $href. */
        if (!isset($a[1])) {
            $separator = '?';
        } elseif (
            substr($a[1], 0, 2) != 'm=' && strpos($a[1], '&m=') === false
        ) {
            $separator = '&';
        }

        if (isset($separator)) {
            $href .= "{$separator}m=" . gmdate('YmdHis', filemtime($path));
        }
    }

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
    ): self {
        /** Call augmentLocalUrl(). */
        static::augmentLocalUrl($href, $path);

        /** Determine media type from the file unless the type is set in
         *  $attrs. */
        if (!isset($attrs['type'])) {
            $attrs =
            [ 'type' => MediaType::newFromFilename($path) ] + (array)$attrs;
        }

        return new static($href, $attrs);
    }

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
