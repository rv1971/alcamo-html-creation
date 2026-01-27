<?php

namespace alcamo\html_creation;

/**
 * @brief Factory for FileResource objects.
 *
 * @date Last reviewed 2026-01-27
 */
interface FileResourceFactoryInterface
{
    /**
     * @param $relPath relative path.
     *
     * @param $directorySeparator Directory separator used in relative
     * path. Defaults to `/` so that the relative path string is
     * platform-independent by default. If the relative path is a native path
     * on the platform, DIRECTORY_SEPARATOR must be given here.
     */
    public function createFromRelPath(
        string $relPath,
        ?string $directorySeparator = null
    ): FileResource;
}
