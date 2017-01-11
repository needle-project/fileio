<?php
namespace NeedleProject\FileIo;

/**
 * Stub file_get_contents
 * @param $filename
 * @return bool
 */
function file_get_contents($filename)
{
    if (true === FileTest::$applyStub) {
        FileTest::$applyStub = false;
        if (false === FileTest::$disableStubsError) {
            trigger_error("Dummy error!", E_USER_WARNING);
        }
        return false;
    }
    return \file_get_contents($filename);
}
