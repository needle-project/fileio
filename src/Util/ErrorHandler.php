<?php
/**
 * This file is part of the NeedleProject\FileIo package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\FileIo\Util;

use NeedleProject\FileIo\Exception\IOException;

/**
 * Class ErrorHandler
 *
 * @package NeedleProject\FileIo\Util
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2016-2017 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
class ErrorHandler
{
    /**
     * States that the current object is the current error handler
     * @var bool
     */
    protected static $isHandledLocal = false;

    /**
     * @param int|null $level   The error level to be handled
     */
    public static function convertErrorsToExceptions(int $level = null)
    {
        static::$isHandledLocal = true;
        if (is_null($level)) {
            $level = E_ALL;
        }
        set_error_handler(function ($errorNumber, $errorMessage, $errorFile, $errorLine, $errorContext) {
            throw new IOException($errorMessage, $errorNumber);
        }, $level);
    }

    /**
     * Restore predefined error handlers
     */
    public static function restoreErrorHandler()
    {
        if (true === static::$isHandledLocal) {
            \restore_error_handler();
        }
    }
}
