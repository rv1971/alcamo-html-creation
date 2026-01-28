<?php

namespace alcamo\html_creation;

use alcamo\html_creation\element\{
    Icon,
    Img,
    Link,
    Script,
    Stylesheet
};
use alcamo\rdfa\MediaType;
use alcamo\xml_creation\{AbstractNode, Nodes};

/**
 * @brief Factory for Icon, Img, Link, Script and Stylesheet objects
 *
 * @date Last reviewed 2026-01-27
 */
class ResourceLinkFactory
{
    private $fileResourceFactory_; ///< FileResourceFactoryInterface

    public function __construct(
        FileResourceFactoryInterface $fileResourceFactory = null
    ) {
        $this->fileResourceFactory_ = $fileResourceFactory
            ?? new SimpleFileResourceFactory();
    }

    public function getFileResourceFactory(): FileResourceFactoryInterface
    {
        return $this->fileResourceFactory_;
    }

    public function createIconFromFileResource(
        FileResource $fileResource,
        ?array $attrs = null
    ): Icon {
        /** Determine media type from the file unless the type is set in
         *  $attrs. */
        if (isset($attrs['type'])) {
            $type = $attrs['type'] instanceof MediaType
                ? $attrs['type']
                : MediaType::newFromString($attrs['type']);
        } else {
            $type = $fileResource->getMediaType();

            $attrs['type'] = $type;
        }

        /** Determine size information from the file if needed and
         *  possible. */
        if (!isset($attrs['sizes'])) {
            if ($type->getSubtype() == 'svg+xml') {
                $attrs['sizes'] = 'any';
            } else {
                $a = getimagesize($fileResource->getPath());

                if ($a !== false) {
                    $attrs['sizes'] = "{$a[0]}x{$a[1]}";
                }
            }
        }

        return new Icon($fileResource->createHref(), $attrs);
    }

    public function createImgFromFileResource(
        FileResource $fileResource,
        $alt,
        ?array $attrs = null
    ): Img {
        /** Determine size information from the file if needed and
         *  possible. */
        if (!isset($attrs['width']) || !isset($attrs['height'])) {
            $a = getimagesize($fileResource->getPath());

            if ($a !== false) {
                $attrs['width'] = $a[0];
                $attrs['height'] = $a[1];
            }
        }

        return new Img($fileResource->createHref(), $alt, $attrs);
    }

    public function createLinkFromFileResource(
        FileResource $fileResource,
        ?array $attrs = null
    ): Link {
        /** Determine media type from the file unless the type is set in
         *  $attrs. */
        if (!isset($attrs['type'])) {
            $attrs['type'] = $fileResource->getMediaType();
        }

        return new Link($fileResource->createHref(), $attrs);
    }

    public function createScriptFromFileResource(
        FileResource $fileResource,
        ?array $attrs = null
    ): Script {
        $attrs['src'] = $fileResource->createHref();

        /** Determine media type from the file unless the type is set in
         *  $attrs. */
        $type = $attrs['type'] ?? $fileResource->getMediaType();

        /** Set `type` to `module` for an `mjs` file. Omit `type` if any other
         *  javascript file. */
        if ($type == 'application/javascript') {
            if (
                pathinfo($fileResource->getPath(), PATHINFO_EXTENSION) == 'mjs'
            ) {
                $attrs['type'] = 'module';
            } else {
                unset($attrs['type']);
            }
        }

        return new Script(null, $attrs);
    }

    public function createStylesheetFromFileResource(
        FileResource $fileResource,
        ?array $attrs = null
    ): Stylesheet {
        /** If `type` is `text/css` (possibly followed by parameters), unset
         *  it. */
        if (
            isset($attrs['type'])
                && substr($attrs['type'], 0, 8) == 'text/css'
        ) {
            unset($attrs['type']);
        }

        return new Stylesheet($fileResource->createHref(), $attrs);
    }

    /// Create the appropriate element for a file resource
    public function createHtmlFromFileResource(
        FileResource $fileResource,
        ?array $attrs = null
    ): Element {
        /** Determine media type from the file unless the type is set in
         *  $attrs. */
        if (isset($attrs['type'])) {
            $type = $attrs['type'] instanceof MediaType
                ? $attrs['type']
                : MediaType::newFromString($attrs['type']);
        } else {
            $type = $fileResource->getMediaType();

            $attrs['type'] = $type;
        }

        switch ($type->getType()) {
            /** - Create an Icon if resource is an image file. */
            case 'image':
                return $this->createIconFromFileResource(
                    $fileResource,
                    $attrs,
                );
        }

        switch ($type->getTypeAndSubtype()) {
            /** - Create a Script if resource is a JavaScript file. */
            case 'application/javascript':
            case 'module':
                return $this->createScriptFromFileResource(
                    $fileResource,
                    $attrs
                );

             /** - Create a Stylesheet if resource is a CSS file. */
            case 'text/css':
                return $this->createStylesheetFromFileResource(
                    $fileResource,
                    $attrs
                );

            /** - In all other cases, create a Link. $attrs['rel'] must be set
             *  in this case. */
            default:
                return $this->createLinkFromFileResource($fileResource, $attrs);
        }
    }

    /// Create HTML elements from an iterable
    public function createNodesFromItems(iterable $items): Nodes
    {
        $nodes = [];

        foreach ($items as $item) {
            switch (true) {
                /** - If an item is an XML node, use it as-is. */
                case $item instanceof AbstractNode:
                    $nodes[] = $item;
                    break;

                /** - If an item is a FileResource, create HTML from it. */
                case $item instanceof FileResource:
                    $nodes[] = $this->createHtmlFromFileResource($item);
                    break;

                 /** - If an item is an array, then take the first element as
                 * the path. If the second element is an array, take it as an
                 * array of attributes, otherwise as the value for the `rel`
                 * attribute. */
                case is_array($item):
                    $nodes[] = $this->createHtmlFromFileResource(
                        $this->fileResourceFactory_->createFromRelPath($item[0]),
                        isset($item[1])
                            ? (is_array($item[1])
                               ? $item[1]
                               : [ 'rel' => $item[1] ])
                            : null
                    );

                    break;

                /** - In all other cases, take the item as the path. */
                default:
                    $nodes[] = $this->createHtmlFromFileResource(
                        $this->fileResourceFactory_->createFromRelPath($item)
                    );
            }
        }

        return new Nodes($nodes);
    }
}
