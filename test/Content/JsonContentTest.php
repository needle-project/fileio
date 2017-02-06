<?php
namespace NeedleProject\FileIo\Content;

use PHPUnit\Framework\TestCase;

class JsonContentTest extends TestCase
{
    /**
     * @dataProvider providePassContent
     *
     * @param $stringContent
     * @param $expectedArray
     * @param $expectedObj
     * @throws \NeedleProject\FileIo\Exception\ContentException
     */
    public function testPassReadContent($stringContent, $expectedArray, $expectedObj)
    {
        $content = new JsonContent($stringContent);
        $this->assertEquals($expectedArray, $content->getArray());
        $this->assertEquals($expectedObj, $content->getObject());
    }

    /**
     * @dataProvider provideInvalidJson
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
     * @dataProvider provideInvalidJson
     *
     * @param $stringContent
     * @expectedException \NeedleProject\FileIo\Exception\ContentException
     */
    public function testExceptionContentObject($stringContent)
    {
        $content = new JsonContent($stringContent);
        $content->getObject();
    }

    /**
     * @return array
     */
    public function providePassContent(): array
    {
        $returnList = [];
        // scenario 1
        $stbObject = new \stdClass();
        $stbObject->a = 'b';
        $returnList[] = [
            '{"a":"b"}', ['a' => 'b'], $stbObject
        ];
        return $returnList;
    }

    public function provideInvalidJson(): array
    {
        return [
            // JSON_ERROR_SYNTAX
            ["a:b"],
            // JSON_ERROR_UTF8
            ["\xB1\x31"]
        ];
    }
}
