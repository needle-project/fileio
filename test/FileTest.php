<?php
namespace NeedleProject\FileIo;

class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @const string Fixture default directory
     */
    const FIXTURE_PATH = __DIR__ . DIRECTORY_SEPARATOR . 'fixture' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;

    /**
     * Test setUp
     */
    public function setUp()
    {
        // create fixture
        touch(static::FIXTURE_PATH . 'readable.file');
        touch(static::FIXTURE_PATH . 'unreadable.file');
        chmod(static::FIXTURE_PATH . 'unreadable.file', 0333);
        touch(static::FIXTURE_PATH . 'unwritable.file');
        chmod(static::FIXTURE_PATH . 'unwritable.file', 555);
        touch(static::FIXTURE_PATH . 'delete.file');
    }

    /**
     * Test tearDown
     */
    public function tearDown()
    {
        unlink(static::FIXTURE_PATH . 'readable.file');
        unlink(static::FIXTURE_PATH . 'unreadable.file');
        unlink(static::FIXTURE_PATH . 'unwritable.file');
    }

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
     * Test ::exists method
     * @dataProvider provisionFakeFiles
     * @param $providedFile
     */
    public function testExistsFalse($providedFile)
    {
        $file = new File($providedFile);
        $this->assertFalse($file->exists(), sprintf("%s should not exist!", $providedFile));
    }

    /**
     * @param $providedFile
     * @dataProvider provisionReadableFiles
     */
    public function testIsReadableTrue($providedFile)
    {
        $file = new File($providedFile);
        $this->assertTrue($file->isReadable(), sprintf("%s should have permissions for read!", $providedFile));
    }

    /**
     * @param $providedFile
     * @dataProvider provisionUnreadableFiles
     */
    public function testIsReadableFalse($providedFile)
    {
        $file = new File($providedFile);
        $this->assertFalse($file->isReadable(), sprintf("%s should not have permissions for read!", $providedFile));
    }

    /**
     * @param $providedFile
     * @dataProvider provisionReadableFiles
     */
    public function testIsWritableTrue($providedFile)
    {
        $file = new File($providedFile);
        $this->assertTrue($file->isWritable(), sprintf("%s should be writable!", $providedFile));
    }

    /**
     * @param $providedFile
     * @dataProvider provisionUnwritableFiles
     */
    public function testIsWritableFalse($providedFile)
    {
        $file = new File($providedFile);
        $this->assertFalse($file->isWritable(), sprintf("%s should not be writable!", $providedFile));
    }

    /**
     * @param $providedFile
     * @dataProvider providionFilesToDelete
     */
    public function testDelete($providedFile)
    {
        $file = new File($providedFile);
        $this->assertTrue($file->exists(), sprintf("%s should exists!", $providedFile));
        $file->delete();
        $this->assertFalse($file->exists(), sprintf("%s should be deleted!", $providedFile));
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

    /**
     * Provide fake files useful for test scenarios
     * @return array
     */
    public function provisionFakeFiles(): array
    {
        return [
            [__DIR__ . DIRECTORY_SEPARATOR . 'foo.bar']
        ];
    }

    /**
     * Provide real readable file
     * @return array
     */
    public function provisionReadableFiles(): array
    {
        return [
            [__FILE__]
        ];
    }

    /**
     * Provide unreadable file
     * @return array
     */
    public function provisionUnreadableFiles(): array
    {
        return [
            [static::FIXTURE_PATH . 'unreadable.file']
        ];
    }

    /**
     * Provide file that cannot be written
     * @return array
     */
    public function provisionUnwritableFiles(): array
    {
        return [
            [static::FIXTURE_PATH . 'unwritable.file']
        ];
    }

    public function providionFilesToDelete(): array
    {
        return [
            [static::FIXTURE_PATH . 'delete.file']
        ];
    }
}
