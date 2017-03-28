<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo\Content;

use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlContent
 *
 * @package NeedleProject\FileIo\Content
 */
class YamlContent implements ArrayContentInterface
{
    /**
     * @var null|string
     */
    private $content = null;

    /**
     * Content constructor.
     *
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->content;
    }

    /**
     * Return the content as an array
     *
     * @return array
     * @throws \NeedleProject\FileIo\Exception\ContentException
     */
    public function getArray(): array
    {
        return Yaml::parse($this->content);
    }
}
