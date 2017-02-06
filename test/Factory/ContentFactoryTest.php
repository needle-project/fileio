<?php
namespace NeedleProject\FileIo\Factory;

use NeedleProject\FileIo\Content\Content;
use NeedleProject\FileIo\Content\JsonContent;
use PHPUnit\Framework\TestCase;

class ContentFactoryTest extends TestCase
{
    /**
     * @var null|ContentFactory
     */
    private $factoryInstance = null;

    /**
     * Test setup
     */
    public function setUp()
    {
        $this->factoryInstance = new ContentFactory();
    }

    /**
     * Tear down setup
     */
    public function tearDown()
    {
        $this->factoryInstance = null;
    }

    /**
     * Test factory for text files content
     */
    public function testCreateForTxt()
    {
        $content = $this->factoryInstance->create(ContentFactory::EXT_TXT, "foo");
        $this->assertInstanceOf(Content::class, $content);
        $this->assertEquals('foo', $content->get());
    }

    /**
     * Test factory for text files content
     */
    public function testCreateForDefault()
    {
        $content = $this->factoryInstance->create('foo_extension', "foo");
        $this->assertInstanceOf(Content::class, $content);
        $this->assertEquals('foo', $content->get());
    }

    /**
     * Test factory for json files content
     * @todo Update when JsonType will be implemented
     */
    public function testCreateForJson()
    {
        $content = $this->factoryInstance->create(ContentFactory::EXT_JSON, "foo");
        $this->assertInstanceOf(JsonContent::class, $content);
        $this->assertEquals('foo', $content->get());
    }
}
