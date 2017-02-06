<?php
namespace NeedleProject\FileIo\Content;

use PHPUnit\Framework\TestCase;

class ContentTest extends TestCase
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
