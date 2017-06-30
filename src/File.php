<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo;

use NeedleProject\FileIo\Content\ContentInterface;
use NeedleProject\FileIo\Exception\FileNotFoundException;
use NeedleProject\FileIo\Exception\IOException;
use NeedleProject\FileIo\Exception\PermissionDeniedException;
use NeedleProject\FileIo\Factory\ContentFactory;
use NeedleProject\FileIo\Helper\PathHelper;
use NeedleProject\Common\Util\ErrorToExceptionConverter;

/**
 * Class File
 *
 * @package NeedleProject\FileIo
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2017 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
class File
{
    /**
     * File extension separator
     * @const string
     */
    const EXTENSION_SEPARATOR = '.';

    /**
     * File's name including the path
     * @var null|string
     */
    private $filenameWithPath = null;

    /**
     * File's extension - For no extension a blank string will be used
     * @var null|string
     */
    private $extension = null;

    /**
     * File's name without extension
     * @var null|string
     */
    private $name = null;

    /**
     * Whether the file has an extension or if it is set by us
     * @var bool
     */
    private $hasExtension = false;

    /**
     * @var null|ContentFactory
     */
    private $contentFactory = null;

    /**
     * @var null|ErrorToExceptionConverter
     */
    private $errorHandler = null;

    /**
     * File constructor.
     *
     * @param string $filenameWithPath
     */
    public function __construct($filenameWithPath)
    {
        $pathHelper = new PathHelper();
        $this->filenameWithPath = $pathHelper->normalizePathSeparator($filenameWithPath);
        $filename = $pathHelper->extractFilenameFromPath($this->filenameWithPath);
        if (empty($filename) || false === $this->validatePath($this->filenameWithPath)) {
            throw new \RuntimeException(
                sprintf('Given path %s does not represents a file!', $filenameWithPath)
            );
        }
        list($this->name, $this->extension) = $pathHelper->splitFilename($filename);
        $this->hasExtension = (bool)$this->extension;
        $this->errorHandler = new ErrorToExceptionConverter();
    }

    /**
     * States whether the file actually exists on disk
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->filenameWithPath);
    }

    /**
     * States whether the file is readable
     * @return bool
     */
    public function isReadable()
    {
        return is_readable($this->filenameWithPath);
    }

    /**
     * @return bool
     */
    public function isWritable()
    {
        if ($this->exists()) {
            return is_writable($this->filenameWithPath);
        }
        $parts = explode(DIRECTORY_SEPARATOR, $this->filenameWithPath);
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
    public function write(ContentInterface $content)
    {
        if ($this->isWritable() === false) {
            throw new PermissionDeniedException("The current file is not writable!");
        }
        file_put_contents($this->filenameWithPath, $content->get());
        return $this;
    }

    /**
     * @return \NeedleProject\FileIo\Content\ContentInterface
     * @throws \NeedleProject\FileIo\Exception\FileNotFoundException
     * @throws \NeedleProject\FileIo\Exception\IOException
     * @throws \NeedleProject\FileIo\Exception\PermissionDeniedException
     */
    public function getContent()
    {
        if ($this->exists() === false) {
            throw new FileNotFoundException(sprintf("%s does not exists!", $this->filenameWithPath));
        }
        if ($this->isReadable() === false) {
            throw new PermissionDeniedException(
                sprintf("You do not have permissions to read file %s!", $this->filenameWithPath)
            );
        }
        $this->convertErrors();
        $stringContent = file_get_contents($this->filenameWithPath);
        $this->resetErrorHandler();
        if (false === $stringContent) {
            throw new IOException(
                sprintf("Could not retrieve content! Error message: %s", error_get_last()['message'])
            );
        }
        return $this->getContentFactory()
            ->create($this->extension, $stringContent);
    }

    /**
     * Add content to the begging of the file
     * @param string $content
     * @return $this
     * @throws \NeedleProject\FileIo\Exception\PermissionDeniedException
     */
    public function appendContent($content)
    {
        if ($this->isWritable() === false) {
            throw new PermissionDeniedException("The current file is not writable!");
        }
        file_put_contents($this->filenameWithPath, $content, FILE_APPEND);
        return $this;
    }

    /**
     * Add content to the begging of the file
     * @param string $content
     * @return $this
     * @throws \NeedleProject\FileIo\Exception\PermissionDeniedException
     */
    public function prependContent($content)
    {
        if ($this->isWritable() === false) {
            throw new PermissionDeniedException("The current file is not writable!");
        }
        file_put_contents($this->filenameWithPath, $content . $this->getContent()->get());
        return $this;
    }

    /**
     * Deletes the current file
     * @return bool
     * @throws \NeedleProject\FileIo\Exception\IOException
     */
    public function delete()
    {
        if ($this->exists() === false) {
            return false;
        }
        $this->convertErrors();
        $unlinkResult = unlink($this->filenameWithPath);
        $this->resetErrorHandler();
        return $unlinkResult;
    }

    /**
     * State existence of a file's extension
     * @return bool
     */
    public function hasExtension()
    {
        return $this->hasExtension;
    }

    /**
     * Get file's extension
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Get file's name without extension
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get file's name with extension
     * @return string
     */
    public function getBasename()
    {
        if (false === $this->hasExtension()) {
            return $this->name;
        }
        return $this->name . static::EXTENSION_SEPARATOR . $this->extension;
    }

    /**
     * Returns a factory responsible for creating appropriate content
     * @return \NeedleProject\FileIo\Factory\ContentFactory
     */
    protected function getContentFactory()
    {
        if (is_null($this->contentFactory)) {
            $this->contentFactory = new ContentFactory();
        }
        return $this->contentFactory;
    }

    /**
     * Validate if the given path is not a directory
     * @param string $filenameWithPath
     * @return bool
     */
    private function validatePath($filenameWithPath)
    {
        return !($this->exists() && is_dir($filenameWithPath));
    }

    /**
     * Convert errors to Exception type objects
     */
    protected function convertErrors()
    {
        $this->errorHandler->convertErrorsToExceptions(E_ALL, IOException::class);
    }

    /**
     * Undo error to exception conversion
     */
    protected function resetErrorHandler()
    {
        $this->errorHandler->restoreErrorHandler();
    }
}
