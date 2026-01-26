<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<meta>
 *
 * @date Last reviewed 2026-01-20
 */
class Meta extends AbstractSpecificElement
{
    public const TAG_NAME = "meta";

    /**
     * @param $name `name` attribute.
     *
     * @param $content `content` attribute.
     *
     * @param $attrs Further attributes. If $name / $content is set, it
     * overrides `$attrs['name']` / `$attrs['content']`.
     */
    public static function newFromNameContent(
        string $name,
        $content,
        ?array $attrs = null
    ): self {
        return new self(compact('name', 'content') + $attrs);
    }

    /// \<meta> elements have no content, only attributes
    public function __construct(array $attrs)
    {
        parent::__construct(null, $attrs);
    }
}
