<?php
namespace NeedleProject\FileIo\Content;

class ContentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider contentProvider
     * @param $providedContent
     */
    public function testGet($providedContent)
    {
        $content = new Content($providedContent);
        $this->assertEquals(
            $providedContent,
            $content->get(),
            sprintf("Provided content does not equals to the one returned!")
        );
    }

    /**
     * @return array
     */
    public function contentProvider(): array
    {
        return [
            ['foo'],
            ['bar'],
            [1],
            [0xFF]
        ];
    }
}
