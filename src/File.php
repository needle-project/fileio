<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo;

use NeedleProject\FileIo\Content\ContentInterface;

/**
 * Class File
 *
 * @package NeedleProject\FileIo
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2016-2017 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
class File
{
    private $filename = null;

    /**
     * File constructor.
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * States whether the file actually exists on disk
     * @return bool
     */
    public function exists(): bool
    {
        return file_exists($this->filename);
    }

    /**
     * States whether the file is readable
     * @return bool
     */
    public function isReadable(): bool
    {
        return is_readable($this->filename);
    }

    /**
     * @return bool
     */
    public function isWritable(): bool
    {
        return is_writable($this->filename);
    }

    /**
     * Write content to the current file
     * @param ContentInterface $content
     * @return \NeedleProject\FileIo\File
     */
    public function write(ContentInterface $content): File
    {
        file_put_contents($this->filename, $content->get());
        return $this;
    }

    /**
     * Deletes the current file
     */
    public function delete()
    {
        unlink($this->filename);
    }
}
