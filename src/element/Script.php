<?php

namespace alcamo\html_creation\element;

use alcamo\rdfa\MediaType;

/**
 * @brief HTML element \<script>
 *
 * @date Last reviewed 2026-01-27
 */
class Script extends AbstractScriptSupportingElement
{
    public const TAG_NAME = "script";

    public function __construct($content = null, ?array $attrs = null)
    {
        /**
         * There are browsers which get confused with empty \<script> tags.
         */
        parent::__construct($content ?? '', $attrs);
    }
}
