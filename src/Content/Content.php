<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo\Content;

/**
 * Class Content
 *
 * @package NeedleProject\FileIo\Content
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2017 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
class Content implements ContentInterface
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
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->content;
    }

    /**
     * Return the content as an array
     *
     * @return array
     * @throws \NeedleProject\FileIo\Exception\ContentException
     */
    public function getArray()
    {
        return [$this->content];
    }

    /**
     * @return mixed|\stdClass
     * @throws \NeedleProject\FileIo\Exception\ContentException
     */
    public function getObject()
    {
        return (object)['content' => $this->content];
    }
}
