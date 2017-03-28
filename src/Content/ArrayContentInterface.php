<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo\Content;

/**
 * Interface ArrayContentInterface
 *
 * @package NeedleProject\FileIo\Content
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2017 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
interface ArrayContentInterface extends ContentInterface
{
    /**
     * @return array
     */
    public function getArray(): array;
}
