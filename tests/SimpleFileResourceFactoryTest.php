<?php

namespace alcamo\html_creation;

use alcamo\exception\SyntaxError;
use PHPUnit\Framework\TestCase;

class SimpleFileResourceFactoryTest extends TestCase
{
    public const BASE_DIR = __DIR__ . DIRECTORY_SEPARATOR;

    public const BASE2_DIR = __DIR__ . DIRECTORY_SEPARATOR
         . '..' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR;

    /**
     * @dataProvider basicsProvider
     */
    public function testBasics(
        $baseDir,
        $uriPrefix,
        $preferGz,
        $relPath,
        $directorySeparator,
        $expectedFileResource
    ): void {
        $factory = SimpleFileResourceFactory::newFromProps(
            (object)compact('baseDir', 'uriPrefix', 'preferGz')
        );

        $this->assertSame($uriPrefix, $factory->getUriPrefix());

        $this->assertSame((bool)$preferGz, $factory->doesPreferGz());

        $this->assertEquals(
            $expectedFileResource,
            $factory->createFromRelPath($relPath, $directorySeparator)
        );
    }

    public function basicsProvider(): array
    {
        return [
            [
                self::BASE_DIR,
                'http://www.example.org/file=',
                null,
                '../tests/alcamo.css',
                null,
                new FileResource(
                    self::BASE2_DIR . 'alcamo.css',
                    'http://www.example.org/file=../tests/alcamo.css',
                    false
                )
            ],
            [
                __DIR__,
                'http://www.example.org/file=',
                true,
                '..\\tests\\alcamo.ico',
                '\\',
                new FileResource(
                    self::BASE2_DIR . 'alcamo.ico',
                    'http://www.example.org/file=../tests/alcamo.ico',
                    true
                )
            ]
        ];
    }

    public function testException(): void
    {
        $factory = new SimpleFileResourceFactory();

        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "/foo"; relative path starts with /'
        );

        $factory->createFromRelPath('/foo');
    }
}
