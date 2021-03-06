<?php
namespace NeedleProject\FileIo;

use NeedleProject\FileIo\Content\Content;
use NeedleProject\FileIo\Content\JsonContent;
use NeedleProject\FileIo\Content\YamlContent;
use PHPUnit_Framework_TestCase as TestCase;

class FileTest extends TestCase
{
    /**
     * If we should apply a stub
     * @var bool
     */
    static public $applyStub = false;

    /**
     * If the the stub should trigger an error
     * @var bool
     */
    static public $disableStubsError = false;

    /**
     * @const string Fixture default directory
     */
    public static $FIXTURE_PATH = null;

    /**
     * FileTest constructor.
     *
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        static::$FIXTURE_PATH = __DIR__ . DIRECTORY_SEPARATOR . 'fixture'
            . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Test setUp
     */
    public function setUp()
    {
        // create fixture
        touch(static::$FIXTURE_PATH . 'readable.file');
        touch(static::$FIXTURE_PATH . 'unreadable.file');
        chmod(static::$FIXTURE_PATH . 'unreadable.file', 0333);
        touch(static::$FIXTURE_PATH . 'unwritable.file');
        chmod(static::$FIXTURE_PATH . 'unwritable.file', 0555);
        touch(static::$FIXTURE_PATH . 'delete.file');
        touch(static::$FIXTURE_PATH . 'content.file');
        touch(static::$FIXTURE_PATH . 'file_with_content');
        static::$applyStub = false;
        static::$disableStubsError = false;
    }

    /**
     * Test tearDown
     */
    public function tearDown()
    {
        unlink(static::$FIXTURE_PATH . 'readable.file');
        unlink(static::$FIXTURE_PATH . 'unreadable.file');
        unlink(static::$FIXTURE_PATH . 'unwritable.file');
        if (file_exists(static::$FIXTURE_PATH . 'delete.file')) {
            unlink(static::$FIXTURE_PATH . 'delete.file');
        }
        unlink(static::$FIXTURE_PATH . 'content.file');
        unlink(static::$FIXTURE_PATH . 'file_with_content');
    }

    /**
     * Test ::exists method
     * @dataProvider provideRealFiles
     * @param $providedFile
     */
    public function testExistsTrue($providedFile)
    {
        $file = new File($providedFile);
        $this->assertTrue($file->exists(), sprintf("%s actually exists on disk!", $providedFile));
    }

    /**
     * Test ::exists method
     * @dataProvider provideFakeFiles
     * @param $providedFile
     */
    public function testExistsFalse($providedFile)
    {
        $file = new File($providedFile);
        $this->assertFalse($file->exists(), sprintf("%s should not exist!", $providedFile));
    }

    /**
     * @param $providedFile
     * @dataProvider provideRealFiles
     */
    public function testIsReadableTrue($providedFile)
    {
        $file = new File($providedFile);
        $this->assertTrue($file->isReadable(), sprintf("%s should have permissions for read!", $providedFile));
    }

    /**
     * @param $providedFile
     * @dataProvider provideUnreadableFiles
     */
    public function testIsReadableFalse($providedFile)
    {
        $file = new File($providedFile);
        $this->assertFalse($file->isReadable(), sprintf("%s should not have permissions for read!", $providedFile));
    }

    /**
     * @param $providedFile
     * @dataProvider provideRealFiles
     * @dataProvider provideWritableFiles
     */
    public function testIsWritableTrue($providedFile)
    {
        $file = new File($providedFile);
        $this->assertTrue($file->isWritable(), sprintf("%s should be writable!", $providedFile));
    }

    /**
     * @param $providedFile
     * @dataProvider provideUnwritableFiles
     */
    public function testIsWritableFalse($providedFile)
    {
        $file = new File($providedFile);
        $this->assertFalse($file->isWritable(), sprintf("%s should not be writable!", $providedFile));
    }

    /**
     * Test ::delete method
     */
    public function testDeleteTrue()
    {
        $providedFile = static::$FIXTURE_PATH . 'delete.file';
        $file = new File($providedFile);
        $this->assertTrue($file->delete(), sprintf("%s should be deleted!", $providedFile));
    }

    /**
     * @param $providedFile
     * @dataProvider provideFakeFiles
     */
    public function testDeleteFalse($providedFile)
    {
        $file = new File($providedFile);
        $this->assertFalse($file->delete(), sprintf("%s should not be deleted!", $providedFile));
    }

    /**
     * @param $providedContent
     * @dataProvider provideContent
     */
    public function testWrite($providedContent)
    {
        $filename = static::$FIXTURE_PATH . 'file_with_content';

        $file = new File($filename);
        $file->write($providedContent);

        $content = file_get_contents($filename);
        $this->assertEquals($providedContent->get(), $content, "Content written is not equal to the one expected!");
    }

    /**
     * @param $providedFile
     * @dataProvider provideUnwritableFiles
     * @expectedException \NeedleProject\FileIo\Exception\PermissionDeniedException
     */
    public function testWriteFail($providedFile)
    {
        $file = new File($providedFile);
        $file->write(new Content('foo'));
    }

    /**
     * @param $providedFile
     * @dataProvider provideUnreadableFiles
     * @expectedException NeedleProject\FileIo\Exception\PermissionDeniedException
     */
    public function testGetContentUnreadableFile($providedFile)
    {
        $file = new File($providedFile);
        $file->getContent();
    }

    /**
     * @param $providedFile
     * @dataProvider provideFakeFiles
     * @expectedException NeedleProject\FileIo\Exception\FileNotFoundException
     */
    public function testGetContentNonExistentFile($providedFile)
    {
        $file = new File($providedFile);
        $file->getContent();
    }

    /**
     * @param $providedFile
     * @dataProvider provideRealFiles
     * @expectedException NeedleProject\FileIo\Exception\IOException
     * @expectedExceptionMessage Dummy error!
     */
    public function testGetContentConvertedException($providedFile)
    {
        require_once 'stub/file_get_contents.php';
        static::$applyStub = true;

        $file = new File($providedFile);
        $file->getContent();
    }

    /**
     * @param $providedFile
     * @dataProvider provideRealFiles
     * @expectedException NeedleProject\FileIo\Exception\IOException
     * @expectedExceptionMessageRegExp /Could not retrieve content! Error message:/
     */
    public function testGetContentFalse($providedFile)
    {
        require_once 'stub/file_get_contents.php';
        static::$applyStub = true;
        static::$disableStubsError = true;

        $file = new File($providedFile);
        $file->getContent();
    }

    /**
     * We will test that we receive the desired content
     *
     * @param $providedFile
     * @param $providedContent
     *
     * @dataProvider provideFileAndContent
     */
    public function testGetContentPass($providedFile, $providedContent)
    {
        file_put_contents($providedFile, $providedContent);
        $file = new File($providedFile);
        $content = $file->getContent();
        $this->assertEquals($providedContent, $content->get(), "The content is not the same!");
    }

    /**
     * Test extension
     *
     * @param $filename
     * @param $extension
     *
     * @dataProvider provideFileAndExtension
     */
    public function testGetExtension($filename, $extension)
    {
        $file = new File($filename);
        $this->assertEquals(
            $extension,
            $file->getExtension(),
            sprintf("Expected extension %s, received %s", $extension, $file->getExtension())
        );
    }

    /**
     * Test has extension
     *
     * @param $filename
     *
     * @dataProvider provideFilesWithExtension
     */
    public function testHasExtensionTrue($filename)
    {
        $file = new File($filename);
        $this->assertTrue(
            $file->hasExtension(),
            sprintf("File %s should have extension", $filename)
        );
    }

    /**
     * Test that it does not have an extension
     *
     * @param $filename
     *
     * @dataProvider provideFilesWithoutExtension
     */
    public function testHasExtensionFalse($filename)
    {
        $file = new File($filename);
        $this->assertFalse(
            $file->hasExtension(),
            sprintf("File %s should not have an extension", $filename)
        );
    }

    /**
     * Test name retrieval
     *
     * @dataProvider provideFileAnExpectedNames
     * @param $filename
     * @param $expectedName
     */
    public function testGetName($filename, $expectedName)
    {
        $file = new File($filename);
        $this->assertEquals(
            $expectedName,
            $file->getName(),
            sprintf("Expected extension %s, received %s", $expectedName, $file->getName())
        );
    }

    /**
     * Test name retrieval
     *
     * @dataProvider provideFileAnExpectedBasenames
     * @param $filename
     * @param $expectedBasename
     */
    public function testGetBasename($filename, $expectedBasename)
    {
        $file = new File($filename);
        $this->assertEquals(
            $expectedBasename,
            $file->getBasename(),
            sprintf("Expected extension %s, received %s", $expectedBasename, $file->getBasename())
        );
    }

    /**
     * @expectedException \RuntimeException
     * @dataProvider provideExceptionalCreationScenario
     * @param $filePath
     */
    public function testFileCreationWithDir($filePath)
    {
        new File($filePath);
    }


    /**
     * @dataProvider provideContentChunks
     * @param $providedContent
     * @param $extraContent
     */
    public function testPrependWrite($providedContent, $extraContent)
    {
        $filename = static::$FIXTURE_PATH . 'file_with_content';

        $file = new File($filename);
        $file->write($providedContent);
        $file->prependContent($extraContent);

        $content = file_get_contents($filename);
        $this->assertEquals(
            $extraContent . $providedContent->get(),
            $content,
            "Content written is not equal to the one expected!"
        );
    }

    /**
     * @dataProvider provideContentChunks
     * @param $providedContent
     * @param $extraContent
     */
    public function testAppendWrite($providedContent, $extraContent)
    {
        $filename = static::$FIXTURE_PATH . 'file_with_content';

        $file = new File($filename);
        $file->write($providedContent);
        $file->appendContent($extraContent);

        $content = file_get_contents($filename);
        $this->assertEquals(
            $providedContent->get() . $extraContent,
            $content,
            "Content written is not equal to the one expected!"
        );
    }

    /**
     * @dataProvider provideUnwritableFiles
     * @param $filename
     * @expectedException \NeedleProject\FileIo\Exception\IOException
     */
    public function testPrependException($filename)
    {
        $file = new File($filename);
        $file->prependContent('foo');
    }

    /**
     * @dataProvider provideUnwritableFiles
     * @param $filename
     * @expectedException \NeedleProject\FileIo\Exception\IOException
     */
    public function testAppendException($filename)
    {
        $file = new File($filename);
        $file->appendContent('foo');
    }

    /**
     * Provide real files useful for test scenarios
     * @return array
     */
    public function provideRealFiles()
    {
        return [
            [__FILE__]
        ];
    }

    /**
     * Provide fake files useful for test scenarios
     * @return array
     */
    public function provideFakeFiles()
    {
        return [
            [__DIR__ . DIRECTORY_SEPARATOR . 'foo.bar'],
            ['dummy.file']
        ];
    }

    /**
     * Provide unreadable file
     * @return array
     */
    public function provideUnreadableFiles()
    {
        return [
            [static::$FIXTURE_PATH . 'unreadable.file']
        ];
    }

    /**
     * Provide file that cannot be written
     * @return array
     */
    public function provideUnwritableFiles()
    {
        return [
            [static::$FIXTURE_PATH . 'unwritable.file']
        ];
    }

    /**
     * Provide Content
     * @return array
     */
    public function provideContent()
    {
        return [
            [new Content('foo')],
            [new Content('bar')],
            [new JsonContent('{"bar":"foo"}')],
            [new YamlContent("foo: bar")]
        ];
    }

    /**
     * @return array
     */
    public function provideContentChunks()
    {
        return [
            [new Content('foo'), "bar"],
            [new JsonContent('{"bar":"foo"}'), "foo"],
            [new YamlContent("foo: bar"), "foo"],
        ];
    }

    /**
     * Provide a filename and a content
     * @return array
     */
    public function provideFileAndContent()
    {
        return [
            [static::$FIXTURE_PATH . 'content.file', 'Lorem ipsum']
        ];
    }

    /**
     * Provide a writable file and also a writable path
     * @return array
     */
    public function provideWritableFiles()
    {
        return [
            [__FILE__],
            [__DIR__ . DIRECTORY_SEPARATOR . 'foo.bar'],
        ];
    }

    /**
     * Provide filenames and their expected extensions
     * @return array
     */
    public function provideFileAndExtension()
    {
        return [
            [__FILE__, 'php'],
            ['.htaccess', 'htaccess'],
            ['name', ''],
            ['a.combined.filename.with.multiple.ext.separator', 'separator']
        ];
    }

    /**
     * @return array
     */
    public function provideFilesWithExtension()
    {
        return [
            [__FILE__],
            ['.htaccess'],
            ['a.combined.filename.with.multiple.ext.separator']
        ];
    }

    /**
     * @return array
     */
    public function provideFilesWithoutExtension()
    {
        return [
            [pathinfo(__FILE__, PATHINFO_FILENAME)],
            ['name']
        ];
    }

    /**
     * Provide filenames and their expected extensions
     * @return array
     */
    public function provideFileAnExpectedNames()
    {
        return [
            [__FILE__, pathinfo(__FILE__, PATHINFO_FILENAME)],
            ['.htaccess', ''],
            ['name', 'name'],
            ['a.combined.filename.with.multiple.ext.separator', 'a.combined.filename.with.multiple.ext']
        ];
    }

    /**
     * Provide filenames and their expected extensions
     * @return array
     */
    public function provideFileAnExpectedBasenames()
    {
        return [
            [__FILE__, pathinfo(__FILE__, PATHINFO_BASENAME)],
            [__DIR__ . DIRECTORY_SEPARATOR . '.htaccess', '.htaccess'],
            ['.htaccess', '.htaccess'],
            ['name', 'name'],
            ['a.combined.filename.with.multiple.ext.separator', 'a.combined.filename.with.multiple.ext.separator']
        ];
    }

    /**
     * Provide scenarios that will fail to create the file
     * @return array
     */
    public function provideExceptionalCreationScenario()
    {
        return [
            [__DIR__],
            [__DIR__ . DIRECTORY_SEPARATOR]
        ];
    }
}
