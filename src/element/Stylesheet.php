<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<link> referring to a stylesheet
 *
 * @date Last reviewed 2026-01-27
 */
class Stylesheet extends Link
{
    public const DEFAULT_ATTRS = [ 'rel' => 'stylesheet' ];
}
