<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo;

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
        return false;
    }

    /**
     * States whether the file is readable
     * @return bool
     */
    public function isReadable(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isWritable(): bool
    {
        return false;
    }

    /**
     * Write content to the current file
     * @param mixed $content
     * @return \NeedleProject\FileIo\File
     */
    public function write(/* Content */$content): File
    {
        return $this;
    }

    /**
     * Deletes the current file
     */
    public function delete()
    {
        // do nothing at the moment
    }
}
