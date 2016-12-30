<?php
namespace NeedleProject\FileIo;

class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test ::exists method
     * @dataProvider provisionRealFiles
     * @param $providedFile
     */
    public function testExistsTrue($providedFile)
    {
        $file = new File($providedFile);
        $this->assertTrue($file->exists(), sprintf("%s actually exists on disk!", $providedFile));
    }

    /**
     * Provide real files useful for test scenarios
     * @return array
     */
    public function provisionRealFiles(): array
    {
        return [
            [__FILE__]
        ];
    }
}
