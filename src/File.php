<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo;

use NeedleProject\FileIo\Content\Content;
use NeedleProject\FileIo\Content\ContentInterface;
use NeedleProject\FileIo\Exception\FileNotFoundException;
use NeedleProject\FileIo\Exception\IOException;
use NeedleProject\FileIo\Exception\PermissionDeniedException;
use NeedleProject\FileIo\Util\ErrorHandler;

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
    /**
     * File's name including the path
     * @var null|string
     */
    private $filename = null;

    /**
     * File constructor.
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = preg_replace('#(\\\|\/)#', DIRECTORY_SEPARATOR, $filename);
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
        if ($this->exists()) {
            return is_writable($this->filename);
        }
        $parts = explode(DIRECTORY_SEPARATOR, $this->filename);
        array_pop($parts);
        return is_writable(implode(DIRECTORY_SEPARATOR, $parts));
    }

    /**
     * Write content to the current file
     *
     * @param \NeedleProject\FileIo\Content\ContentInterface $content
     * @return \NeedleProject\FileIo\File
     * @throws \NeedleProject\FileIo\Exception\PermissionDeniedException
     */
    public function write(ContentInterface $content): File
    {
        if ($this->isWritable() === false) {
            throw new PermissionDeniedException("The current file is not writable!");
        }
        file_put_contents($this->filename, $content->get());
        return $this;
    }

    /**
     * @return \NeedleProject\FileIo\Content\ContentInterface
     * @throws \NeedleProject\FileIo\Exception\FileNotFoundException
     * @throws \NeedleProject\FileIo\Exception\IOException
     * @throws \NeedleProject\FileIo\Exception\PermissionDeniedException
     */
    public function getContent(): ContentInterface
    {
        if ($this->exists() === false) {
            throw new FileNotFoundException(sprintf("%s does not exists!", $this->filename));
        }
        if ($this->isReadable() === false) {
            throw new PermissionDeniedException(
                sprintf("You do not have permissions to read file %s!", $this->filename)
            );
        }
        ErrorHandler::convertErrorsToExceptions();
        $stringContent = file_get_contents($this->filename);
        ErrorHandler::restoreErrorHandler();
        if (false === $stringContent) {
            throw new IOException(
                sprintf("Could not retrieve content! Error message: %s", error_get_last()['message'])
            );
        }
        return new Content($stringContent);
    }

    /**
     * Deletes the current file
     * @return bool
     * @throws \NeedleProject\FileIo\Exception\IOException
     */
    public function delete(): bool
    {
        if ($this->exists() === false) {
            return false;
        }
        ErrorHandler::convertErrorsToExceptions();
        $unlinkResult = unlink($this->filename);
        ErrorHandler::restoreErrorHandler();
        return $unlinkResult;
    }
}
