<?php
namespace NeedleProject\FileIo\Content;

use PHPUnit_Framework_TestCase as TestCase;

class ContentTest extends TestCase
{
    /**
     * @dataProvider contentProvider
     * @param $providedContent
     */
    public function testGet($providedContent)
    {
        $content = new Content($providedContent);

        // get simple content
        $this->assertEquals(
            $providedContent,
            $content->get(),
            sprintf("Provided content does not equals to the one returned!")
        );

        // get array content
        $this->assertEquals(
            [$providedContent],
            $content->getArray(),
            sprintf("Provided content does not equals to the one returned!")
        );

        // get object content
        $providedObject = new \stdClass();
        $providedObject->content = $providedContent;

        $this->assertEquals(
            $providedObject,
            $content->getObject(),
            sprintf("Provided content does not equals to the one returned!")
        );
    }

    /**
     * @return array
     */
    public function contentProvider()
    {
        return [
            ['foo'],
            ['bar'],
            [1],
            [0xFF]
        ];
    }
}
