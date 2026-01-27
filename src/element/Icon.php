<?php

namespace alcamo\html_creation\element;

use alcamo\rdfa\MediaType;

/**
 * @brief HTML element \<link> referring to an icon
 *
 * @date Last reviewed 2026-01-27
 */
class Icon extends Link
{
    public const DEFAULT_ATTRS = [ 'rel' => 'icon' ];
}
