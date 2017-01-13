<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo\Factory;

use NeedleProject\FileIo\Content\Content;
use NeedleProject\FileIo\Content\ContentInterface;
use NeedleProject\FileIo\Content\JsonContent;

/**
 * Class ContentFactory
 *
 * @package NeedleProject\FileIo\Factory
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2017 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
class ContentFactory
{
    /**
     * @const string
     */
    const EXT_TXT = 'txt';

    /**
     * @const string
     */
    const EXT_JSON = 'json';

    /**
     * Create a ContentInterface based on the extension and string content
     * @param string $extension
     * @param string $content
     * @return \NeedleProject\FileIo\Content\ContentInterface
     */
    public function create(string $extension, string $content): ContentInterface
    {
        switch ($extension) {
            /**
             * Create JSON Content
             */
            case static::EXT_JSON:
                return new JsonContent($content);
                break;
            /**
             * Default TXT Content
             */
            case static::EXT_TXT:
            default:
                return new Content($content);
        }
    }
}
