<?php

namespace alcamo\html_creation;

use alcamo\exception\SyntaxError;

/**
 * @brief Factory for FileResource objects prepending fixed paths
 *
 * @date Last reviewed 2026-01-27
 */
class SimpleFileResourceFactory implements FileResourceFactoryInterface
{
    private $baseDir_;   ///< ?string
    private $uriPrefix_; ///< ?string
    private $preferGz_;  ///< bool

    /**
     * @brief Create from an object with named properties corresponding to the
     * constructor parameters
     *
     * Useful to create instances of this class from configuration parameters.
     */
    public static function newFromProps(object $props): self
    {
        return new static(
            $props->baseDir   ?? null,
            $props->uriPrefix ?? null,
            $props->preferGz  ?? null
        );
    }

    /**
     * @param $baseDir Directory to prepend to a relative path. A directory
     * separator is automaticlly appended if needed.
     *
     * @param $uriPrefix URI to prepend to a relative path.
     *
     * @param $preferGz Whether to prefer a gzipped file, if available. [false]
     */
    public function __construct(
        ?string $baseDir = null,
        ?string $uriPrefix = null,
        ?bool $preferGz = null
    ) {
        if (isset($baseDir) && $baseDir[-1] != DIRECTORY_SEPARATOR) {
            $baseDir .= DIRECTORY_SEPARATOR;
        }

        $this->baseDir_ = $baseDir;
        $this->uriPrefix_ = $uriPrefix;
        $this->preferGz_ = (bool)$preferGz;
    }

    public function getBaseDir(): ?string
    {
        return $this->baseDir_;
    }

    public function getUriPrefix(): ?string
    {
        return $this->uriPrefix_;
    }

    public function doesPreferGz(): bool
    {
        return $this->preferGz_;
    }

    /** @copydoc alcamo::html_creation::FileResourceFactoryInterface::createFromRelPath() */
    public function createFromRelPath(
        string $relPath,
        ?string $directorySeparator = null
    ): FileResource {
        if (!isset($directorySeparator)) {
            $directorySeparator = '/';
        }

        /** @throw alcamo::exception::SyntaxError if $relPath starts with a
         *  directory separator. */
        if ($relPath[0] == $directorySeparator) {
            throw (new SyntaxError())->setMessageContext(
                [
                    'inData' => $relPath,
                    'extraMessage'
                        => "relative path starts with $directorySeparator"
                ]
            );
        }

        return new FileResource(
            $this->baseDir_
            . ($directorySeparator == DIRECTORY_SEPARATOR
               ? $relPath
               : strtr($relPath, $directorySeparator, DIRECTORY_SEPARATOR)),
            $this->uriPrefix_
            . ($directorySeparator == '/'
               ? $relPath
               : strtr($relPath, $directorySeparator, '/')),
            $this->preferGz_
        );
    }
}
