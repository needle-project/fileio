<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo\Content;

use NeedleProject\FileIo\Exception\ContentException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlContent
 *
 * @package NeedleProject\FileIo\Content
 */
class YamlContent extends Content implements ContentInterface
{
    /**
     * Return the content as an array
     *
     * @return array
     * @throws \NeedleProject\FileIo\Exception\ContentException
     */
    public function getArray()
    {
        try {
            return Yaml::parse($this->get(), true);
        } catch (ParseException $e) {
            throw new ContentException(
                sprintf(
                    "YAML could not be parsed: %s",
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     * @return \stdClass
     */
    public function getObject()
    {
        return (object)$this->getArray();
    }
}
