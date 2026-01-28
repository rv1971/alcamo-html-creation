<?php

namespace alcamo\html_creation;

use alcamo\xml_creation\Comment;
use PHPUnit\Framework\TestCase;

class ResourceLinkFactoryTest extends TestCase
{
    public const BASE_DIR = __DIR__ . DIRECTORY_SEPARATOR;

    /**
     * @dataProvider createHtmlFromFileResourceProvider
     */
    public function testCreateHtmlFromFileResource(
        $path,
        $attrs,
        $expectedHtml
    ): void {
        $fileResourceFactory = SimpleFileResourceFactory::newFromProps(
            (object)[ 'baseDir' => self::BASE_DIR ]
        );

        $resourceLinkFactory = new ResourceLinkFactory($fileResourceFactory);

        $this->assertSame(
            $fileResourceFactory,
            $resourceLinkFactory->getFileResourceFactory()
        );

        $this->assertSame(
            $expectedHtml,
            preg_replace(
                '/m=\d{14}/',
                'm=00000000000000',
                $resourceLinkFactory->createHtmlFromFileResource(
                    $fileResourceFactory->createFromRelPath($path),
                    $attrs
                )
            )
        );
    }

    public function createHtmlFromFileResourceProvider(): array
    {
        return [
            'icon' => [
                'alcamo.ico',
                null,
                '<link type="image/vnd.microsoft.icon" sizes="64x64" href="alcamo.ico?m=00000000000000" rel="icon"/>'
            ],
            'icon-svg' => [
                'alcamo.svg',
                [ 'id' => 'icon' ],
                '<link id="icon" type="image/svg+xml" sizes="any" href="alcamo.svg?m=00000000000000" rel="icon"/>'
            ],
            'icon-fixed-type' => [
                'alcamo-16.png',
                [ 'type' => 'image/x-png' ],
                '<link type="image/x-png" sizes="16x16" href="alcamo-16.png?m=00000000000000" rel="icon"/>'
            ],
            'manifest' => [
                'foo.json',
                [ 'rel' => 'manifest' ],
                '<link rel="manifest" type="application/json" href="foo.json?m=00000000000000"/>'
            ],
            'script' => [
                'alcamo.js',
                null,
                '<script src="alcamo.js?m=00000000000000"></script>'
            ],
            'script-module' => [
                'alcamo.mjs',
                null,
                '<script type="module" src="alcamo.mjs?m=00000000000000"></script>'
            ],
            'stylesheet' => [
                'alcamo.css',
                null,
                '<link href="alcamo.css?m=00000000000000" rel="stylesheet"/>'
            ]
        ];
    }

    /**
     * @dataProvider createImgFromFileResourceProvider
     */
    public function testCreateImgFromFileResource(
        $path,
        $alt,
        $attrs,
        $expectedHtml
    ): void {
        $fileResourceFactory = SimpleFileResourceFactory::newFromProps(
            (object)[ 'baseDir' => self::BASE_DIR ]
        );

        $resourceLinkFactory = new ResourceLinkFactory($fileResourceFactory);

        $this->assertSame(
            $expectedHtml,
            preg_replace(
                '/m=\d{14}/',
                'm=00000000000000',
                $resourceLinkFactory->createImgFromFileResource(
                    $fileResourceFactory->createFromRelPath($path),
                    $alt,
                    $attrs
                )
            )
        );
    }

    public function createImgFromFileResourceProvider(): array
    {
        return [
            'svg' => [
                'alcamo.svg',
                'picture',
                [ 'id' => 'alcamo' ],
                '<img id="alcamo" src="alcamo.svg?m=00000000000000" alt="picture"/>'
            ],
            'jpeg' => [
                'alcamo-16.jpeg',
                'icon',
                null,
                '<img width="16" height="16" src="alcamo-16.jpeg?m=00000000000000" alt="icon"/>'
            ]
        ];
    }

    /**
     * @dataProvider createNodesFromItemsProvider
     */
    public function testCreateNodesFromItems($items, $expectedHtml): void
    {
        $fileResourceFactory = SimpleFileResourceFactory::newFromProps(
            (object)[ 'baseDir' => self::BASE_DIR ]
        );

        $resourceLinkFactory = new ResourceLinkFactory($fileResourceFactory);

        $this->assertSame(
            $expectedHtml,
            preg_replace(
                '/m=\d{14}/',
                'm=00000000000000',
                $resourceLinkFactory->createNodesFromItems($items)
            )
        );
    }

    public function createNodesFromItemsProvider(): array
    {
        return [
            [
                [
                    new Comment(' lorem ipsum '),
                    new FileResource(self::BASE_DIR . 'alcamo.js', 'alcamo.js'),
                    'alcamo-32.png',
                    [ 'foo.json', 'manifest' ],
                    [ 'alcamo.css', [ 'id' => 'css' ] ]
                ],
                '<!-- lorem ipsum -->'
                . '<script src="alcamo.js?m=00000000000000"></script>'
                . '<link type="image/png" sizes="32x32" href="alcamo-32.png?m=00000000000000" rel="icon"/>'
                . '<link rel="manifest" type="application/json" href="foo.json?m=00000000000000"/>'
                . '<link id="css" href="alcamo.css?m=00000000000000" rel="stylesheet"/>'
            ]
        ];
    }
}
