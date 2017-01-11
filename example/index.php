<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use NeedleProject\FileIo\File;
use NeedleProject\FileIo\Content\Content;

$filename = __DIR__ . DIRECTORY_SEPARATOR . 'my_first_file';

/**
 * Writing content to a file
 */
$content = new Content('Hello world!');
$file = new File(__DIR__ . DIRECTORY_SEPARATOR . 'my_first_file');
if (true === $file->write($content)) {
    echo "I wrote my first file!\n";
}

/**
 * Verifying file
 */
if (true === $file->exists()) {
    echo sprintf('File %s exists on disk!', $filename) . "\n";
}

if (true === $file->isWritable()) {
    echo sprintf('File %s can be written!', $filename) . "\n";
}

if (true === $file->isReadable()) {
    echo sprintf('File %s can be read!', $filename) . "\n";
}

/**
 * Read content from a file
 */
echo "File content:\n";
echo $file->getContent()->get();
echo "\nEnd of file content!\n";

/**
 * Delete the file
 */
if (true === $file->delete()) {
    echo "The file was successfully deleted!" . "\n";
}
