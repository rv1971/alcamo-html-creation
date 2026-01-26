<?php

namespace alcamo\html_creation;

use alcamo\rdfa\{
    AbstractHttpStmt,
    DcAbstract,
    DcCreator,
    DcFormat,
    DcSource,
    DcTitle,
    Node,
    RdfaData,
    StmtInterface,
    XhvMetaStmt
};
use alcamo\html_creation\element\{A, Link, Meta, Title};
use alcamo\xml_creation\Nodes as HtmlNodes;

class Rdfa2Html
{
    /// Mapping of RDFa properties to attributes of a \<link> element
    public const PROP_TO_LINK_ATTR = [
        'dc:format'   => 'type',
        'dc:language' => 'hreflang',
        'dc:title'    => 'title'
    ];

    /**
     * @param $stmt Statement whose object is not a Node.
     *
     * @return Title|Meta|null
     */
    public function createMetaFromStmt(StmtInterface $stmt): ?Element
    {
        switch (true) {
            case $stmt instanceof DcTitle:
                return new Title($stmt, [ 'property' => 'dc:title' ]);

            case $stmt instanceof DcCreator:
                return Meta::newFromNameContent(
                    'author',
                    $stmt,
                    [ 'property' => 'dc:creator' ]
                );

            case $stmt instanceof DcAbstract:
                return Meta::newFromNameContent(
                    'description',
                    $stmt,
                    [ 'property' => 'dc:abstract' ]
                );

                /** Return `null` for `dc:format` and HTTP-related meta
                 *  data. */
            case $stmt instanceof DcFormat:
            case $stmt instanceof AbstractHttpStmt:
                return null;

            default:
                return new Meta(
                    [
                        'property' => $stmt->getPropCurie(),
                        'content'  => $stmt->getObject()
                    ]
                );
        }
    }

    /**
     * @brief Create value of rel for a \<link> element
     *
     * @return Array of attributes
     */
    public function createLinkAttrsFromStmt(StmtInterface $stmt): array
    {
        $rel = $stmt->getPropCurie();

        /** For statements of type alcamo::rdfa::XhvMetaStmt, the `rel`
         *  attribute is only the unprefixed local name since the
         * [XHTML Metainformation Vocabulary](https://www.w3.org/1999/xhtml/vocab#XHTMLMetaInformationModule)
         *  is the default namespace for `rel` attribute values in
         *  `\<link>`s. Otherwise, `rel` is the statement's property CURIE,
         *  potentially followed by an html-specific relation term.
         */
        switch (true) {
            case $stmt instanceof DcCreator:
                $rel = "$rel author";
                break;

            case $stmt instanceof DcSource:
                $rel = "$rel canonical";
                break;

            case $stmt instanceof XhvMetaStmt:
                $rel = $stmt->getPropLocalName();
                break;
        }

        $attrs = [ 'rel' => $rel, 'href' => (string)$stmt->getObject() ];

        $rdfaData = $stmt->getObject()->getRdfaData();

        if (isset($rdfaData)) {
            foreach (static::PROP_TO_LINK_ATTR as $prop => $attrName) {
                if (isset($rdfaData[$prop])) {
                    $attrs[$attrName] = $rdfaData[$prop]->first();
                }
            }
        }

        return $attrs;
    }

    /**
     * @param $stmt Statement whose object is a Node.
     *
     * @return Link|null The return type allows to return `null`, even though
     * this does not happen in this implementattion, so that derived classes
     * may filter statements.
     */
    public function createLinkFromStmt(StmtInterface $stmt): ?Link
    {
        return new Link(
            $stmt->getObject(),
            $this->createLinkAttrsFromStmt($stmt)
        );
    }

    /** @param $stmt Statement whose object is a Node. */
    public function createAFromStmt(StmtInterface $stmt): A
    {
        $attrs = $this->createLinkAttrsFromStmt($stmt);

        if (isset($attrs['title'])) {
            $title = $attrs['title'];
            unset($attrs['title']);
        } else {
            $title = ucfirst($stmt->getPropLocalName());
        }

        return new A($title, $attrs);
    }

    /// Create \<meta>s and \<link>s
    public function createHtmlFromRdfaData(RdfaData $rdfaData): HtmlNodes
    {
        $htmlNodes = [];

        foreach ($rdfaData as $stmts) {
            foreach ($stmts as $stmt) {
                $element = $stmt->getObject() instanceof Node
                    ? $this->createLinkFromStmt($stmt)
                    : $this->createMetaFromStmt($stmt);

                if (isset($element)) {
                    $htmlNodes[] = $element;
                }
            }
        }

        return new HtmlNodes($htmlNodes);
    }

    /// Create array of `xmlns:` attributes needed to represent RDFa data
    public function createNsAttrMapFromRdfaData(RdfaData $rdfaData): array
    {
        $attrs = [];

        foreach ($rdfaData->createNamespaceMap() as $prefix => $nsName) {
            if ($prefix != 'http') {
                $attrs["xmlns:$prefix"] = $nsName;
            }
        }

        return $attrs;
    }
}
