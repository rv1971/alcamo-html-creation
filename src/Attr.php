<?php

namespace alcamo\html_creation;

use alcamo\xml_creation\Attr as XmlAttr;

/**
 * @brief HTML attribute that can be serialized to HTML text
 *
 * @date Last reviewed 2026-01-20
 */
class Attr extends XmlAttr
{
    /**
     * @copydoc alcamo::xml_creation::NodeInterface::__toString()
     */
    public function __toString(): string
    {
        if (is_bool($this->content_)) {
          /**
           * If content is boolean:
           * - render `true` as attribute with its name as its content
           * (e.g. `checked="checked"`)
           * - render `false` as empty string, i.e. omit the attribute
           */
            return $this->content_ ? "$this->name_=\"$this->name_\"" : '';
        } else {
            return parent::__toString();
        }
    }
}
