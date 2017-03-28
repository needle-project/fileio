<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo\Content;

use NeedleProject\FileIo\Exception\ContentException;

/**
 * Class JsonContent
 *
 * @package NeedleProject\FileIo\Content
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2017 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
class JsonContent implements ArrayContentInterface
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
        $content = json_decode($this->content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ContentException(
                sprintf("Could not decode content, got %s", json_last_error_msg())
            );
        }
        return $content;
    }

    /**
     * @return \stdClass
     * @throws \NeedleProject\FileIo\Exception\ContentException
     */
    public function getObject(): \stdClass
    {
        $content = json_decode($this->content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ContentException(
                sprintf("Could not decode content, got %s", json_last_error_msg())
            );
        }
        return $content;
    }
}
