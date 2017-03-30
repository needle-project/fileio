<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo\Helper;

use NeedleProject\FileIo\File;

/**
 * Class PathHelper
 *
 * @package NeedleProject\FileIo\Helper
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2017 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
class PathHelper
{
    /**
     * Convert path separator to the system's default separator
     *
     * For example:
     * root\\foo/bar\ becomes root:
     * - \\foo\\bar on Windows and
     * - /root/foo/bar on Linux
     *
     * @param string $path
     * @return string
     */
    public function normalizePathSeparator(string $path): string
    {
        $path = preg_replace('#(\\\|\/)#', DIRECTORY_SEPARATOR, $path);
        return preg_replace('#' . DIRECTORY_SEPARATOR . '{2,}#', DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Extract the the filename from a path
     *
     * Example: from /foo/bar/file.ext will result file.ext
     *
     * @param string $filePath
     * @return string
     */
    public function extractFilenameFromPath(string $filePath): string
    {
        $fileParts = explode(DIRECTORY_SEPARATOR, $filePath);
        return array_pop($fileParts);
    }

    /**
     * Split a filename into an array containing the name and extension
     * Example:
     *  file.ext -> [file, ext]
     *  .htaccess -> ['', htaccess]
     *  name -> [name, '']
     *
     * @param string $filename
     * @return array
     */
    public function splitFilename(string $filename): array
    {
        $extension = '';
        $name = $filename;
        if (true === strpos($filename, File::EXTENSION_SEPARATOR)) {
            $filenameParts = explode(File::EXTENSION_SEPARATOR, $filename);
            $extension = array_pop($filenameParts);
            $name = implode(File::EXTENSION_SEPARATOR, $filenameParts);
        }

        return [
            $name,
            $extension
        ];
    }
}
