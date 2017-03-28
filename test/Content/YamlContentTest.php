<?php
namespace NeedleProject\FileIo\Content;

use PHPUnit\Framework\TestCase;;

class YamlContentTest extends TestCase
{
    /**
     * @dataProvider providePassContent
     *
     * @param $stringContent
     * @param $expectedArray
     * @throws \NeedleProject\FileIo\Exception\ContentException
     */
    public function testPassReadContent($stringContent, $expectedArray)
    {
        $content = new YamlContent($stringContent);
        $this->assertEquals($expectedArray, $content->getArray());
    }

    /**
     * @dataProvider provideInvalidYaml
     *
     * @param $stringContent
     * @expectedException \NeedleProject\FileIo\Exception\ContentException
     */
    public function testExceptionContentArray($stringContent)
    {
        $content = new JsonContent($stringContent);
        $content->getArray();
    }

    /**
     * @return array
     */
    public function providePassContent(): array
    {
        $returnList = [];
        // scenario 1
        $returnList[] = [
            'a:
    b', ['a' => 'b']
        ];
        return $returnList;
    }

    public function provideInvalidYaml(): array
    {
        return [
            ["\xB1\x31"]
        ];
    }
}
