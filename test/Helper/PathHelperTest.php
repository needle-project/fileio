<?php
namespace NeedleProject\FileIo\Helper;

class PathHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providePathsToNormalize
     * @param $givenPath
     * @param $expectedNormalizedPath
     */
    public function testNormalizePathSeparator($givenPath, $expectedNormalizedPath)
    {
        $pathHelper = new PathHelper();
        $normalizedPath = $pathHelper->normalizePathSeparator($givenPath);
        $this->assertEquals(
            $expectedNormalizedPath,
            $normalizedPath,
            sprintf("Path %s is not normalized as expected %s", $normalizedPath, $expectedNormalizedPath)
        );
    }

    /**
     * @dataProvider providePathsForFiles
     * @param $givenPath
     * @param $expectedFilename
     */
    public function testExtractFilenameFromPath($givenPath, $expectedFilename)
    {
        $pathHelper = new PathHelper();
        $filename = $pathHelper->extractFilenameFromPath($givenPath);
        $this->assertEquals(
            $expectedFilename,
            $filename,
            sprintf("Extracted filename %s is not as expected %s", $filename, $expectedFilename)
        );
    }

    /**
     * @dataProvider provideSplitFilenameScenarios
     * @param $filename
     * @param $expectedName
     * @param $expectedExtension
     */
    public function testSplitFilename($filename, $expectedName, $expectedExtension)
    {
        $pathHelper = new PathHelper();
        list ($name, $extension) = $pathHelper->splitFilename($filename);
        $this->assertEquals(
            $expectedName,
            $name,
            sprintf("Expected name <%s> is not as expected <%s>", $name, $expectedName)
        );
        $this->assertEquals(
            $expectedExtension,
            $extension,
            sprintf("Expected name <%s> is not as expected <%s>", $extension, $expectedExtension)
        );
    }

    /**
     * Provide scenarios for path normalizer
     * @return array
     */
    public function providePathsToNormalize(): array
    {
        return [
            ['/foo\\bar', DIRECTORY_SEPARATOR . 'foo' . DIRECTORY_SEPARATOR . 'bar'],
            ['\\foo\\bar', DIRECTORY_SEPARATOR . 'foo' . DIRECTORY_SEPARATOR . 'bar'],
            ['/foo/bar', DIRECTORY_SEPARATOR . 'foo' . DIRECTORY_SEPARATOR . 'bar'],
            ['/foo/bar\\', DIRECTORY_SEPARATOR . 'foo' . DIRECTORY_SEPARATOR . 'bar' . DIRECTORY_SEPARATOR],
            ['/\foo\/bar\\', DIRECTORY_SEPARATOR . 'foo' . DIRECTORY_SEPARATOR . 'bar' . DIRECTORY_SEPARATOR]
        ];
    }

    /**
     * Provide paths from whom to extract file-names
     * @return array
     */
    public function providePathsForFiles(): array
    {
        return [
            ['foo' . DIRECTORY_SEPARATOR . 'bar', 'bar'],
            ['foo' . DIRECTORY_SEPARATOR . 'foo.bar', 'foo.bar'],
            ['foo' . DIRECTORY_SEPARATOR . 'bar' . DIRECTORY_SEPARATOR, ''],
            ['', '']
        ];
    }

    /**
     * Provide file-names and their split form in name and extension
     * @return array
     */
    public function provideSplitFilenameScenarios(): array
    {
        return [
            ['foo.bar', 'foo', 'bar'],
            ['.htaccess', '', 'htaccess'],
            ['.', '', ''],
            ['foo', 'foo', ''],
            ['multiple.extension.separator', 'multiple.extension', 'separator']
        ];
    }
}
