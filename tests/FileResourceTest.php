<?php

namespace alcamo\html_creation;

use alcamo\exception\FileNotFound;
use PHPUnit\Framework\TestCase;

class FileResourceTest extends TestCase
{
    public const BASE_DIR = __DIR__ . DIRECTORY_SEPARATOR;

    /**
     * @dataProvider basicsProvider
     */
    public function testBasics(
        $path,
        $uri,
        $preferGz,
        $expectedPath,
        $expectedMtime,
        $expectedMediaType,
        $expectedHref
    ): void {
        $fileResource = new FileResource($path, $uri, $preferGz);

        $this->assertSame($expectedPath, $fileResource->getPath());

        $this->assertSame($expectedMtime, $fileResource->getMtime());

        $this->assertSame(
            $expectedMediaType,
            (string)$fileResource->getMediaType()
        );

        $this->assertSame(
            $expectedHref,
            preg_replace(
                '/m=\d{14}/',
                'm=00000000000000',
                $fileResource->createHref()
            )
        );
    }

    public function basicsProvider(): array
    {
        return [
            'css' => [
                self::BASE_DIR . 'alcamo.css',
                'http://www.example.com/css/alcamo.css',
                null,
                self::BASE_DIR . 'alcamo.css',
                filemtime(self::BASE_DIR . 'alcamo.css'),
                'text/css; charset="us-ascii"',
                'http://www.example.com/css/alcamo.css?m=00000000000000'
            ],
            'css.gz' => [
                self::BASE_DIR . 'alcamo.css',
                'http://www.example.com/alcamo.css?foo=bar',
                true,
                self::BASE_DIR . 'alcamo.css.gz',
                filemtime(self::BASE_DIR . 'alcamo.css.gz'),
                'text/css; charset="us-ascii"',
                'http://www.example.com/alcamo.css.gz?foo=bar&m=00000000000000'
            ],
            'svgz' => [
                self::BASE_DIR . 'alcamo.svg',
                'http://www.example.info/favicon.svg',
                true,
                self::BASE_DIR . 'alcamo.svgz',
                filemtime(self::BASE_DIR . 'alcamo.svgz'),
                'image/svg+xml',
                'http://www.example.info/favicon.svgz?m=00000000000000'
            ],
            'with-m' => [
                self::BASE_DIR . 'alcamo.js',
                'http://www.example.com/js?m=2026-01-27T14:35:42',
                true, /* has no effect because there is no js.gz version */
                self::BASE_DIR . 'alcamo.js',
                filemtime(self::BASE_DIR . 'alcamo.js'),
                'application/javascript',
                'http://www.example.com/js?m=2026-01-27T14:35:42'
            ]
        ];
    }

    public function testException(): void
    {
        $path = 'foo.bar';

        $this->expectException(FileNotFound::class);
        $this->expectExceptionMessage('"foo.bar" not found');

        new FileResource($path, 'http://www.example.biz/foo');
    }
}
