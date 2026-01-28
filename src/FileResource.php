<?php

namespace alcamo\html_creation;

use alcamo\exception\FileNotFound;
use alcamo\rdfa\MediaType;
use alcamo\uri\Uri;
use Psr\Http\Message\UriInterface;

/**
 * @brief Local file resource suitable to be referenced in HTML
 *
 * @date Last reviewed 2026-01-27
 */
class FileResource
{
    private $path_;      ///< string, actual path in the filesystem
    private $uri_;       ///< UriInterface, URI to be published
    private $mtime_;     ///< int
    private $mediaType_; ///< MediaType

    /**
     * @param $path Path in the filesystem.
     *
     * @param $uri URI to be published. Defaults to $path, which is useful for
     * the simple case where $path is relative and can be used 1:1 as a
     * relative url.
     *
     * @param $preferGz Whether to prefer a gzipped file, if
     * available. Default `false`.
     *
     * @attention `$preferGz = true` also changes the URI by appending the
     * appropriate suffix to the URI path. The URI must be constructed so that
     * this results in a URI correctly served by the webserver.
     */
    public function __construct(
        string $path,
        $uri = null,
        ?bool $preferGz = null
    ) {
        if (!is_readable($path)) {
            /** @throw alcamo::exception::FileNotFound if $path is not
             *  readable. */
            throw (new FileNotFound())
                ->setMessageContext([ 'filename' => $path ]);
        }

        if (!isset($uri)) {
            $uri = $path;
        }

        $this->path_ = $path;
        $this->uri_ = $uri instanceof UriInterface ? $uri : new Uri($uri);

        if ($preferGz) {
            /** The gzipped file has the additional suffix .gz except for SVG
             * files where the suffix .svg becomes .svgz. */
            $append = substr($this->path_, -4) == '.svg' ? 'z' : '.gz';

            $gzPath = $this->path_ . $append;

            if (is_readable($gzPath)) {
                $this->mediaType_ = MediaType::newFromFilename($this->path_);
                $this->path_ = $gzPath;

                $this->uri_ =
                    $this->uri_->withPath($this->uri_->getPath() . $append);
            }
        }
    }

    public function getPath(): string
    {
        return $this->path_;
    }

    public function getUri(): Uri
    {
        return $this->uri_;
    }

    public function getMtime(): int
    {
        if (!isset($this->mtime_)) {
            $this->mtime_ = filemtime($this->path_);
        }

        return $this->mtime_;
    }

    public function getMediaType(): MediaType
    {
        if (!isset($this->mediaType_)) {
            $this->mediaType_ = MediaType::newFromFilename($this->path_);
        }

        return $this->mediaType_;
    }

    /**
     * @return Resource URI with additional query parameter `m` for file
     * modification time, if not yet contained in URI.
     */
    public function createHref(): Uri
    {
        parse_str($this->uri_->getQuery(), $params);

        if (isset($params['m'])) {
            return clone $this->uri_;
        } else {
            return $this->uri_->withQuery(
                http_build_query(
                    $params + [ 'm' => gmdate('YmdHis', $this->getMtime()) ]
                )
            );
        }
    }
}
