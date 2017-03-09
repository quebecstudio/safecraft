<?php
namespace Craft;

class SafeCraftVariable
{
    public function getPublicPath()
    {
        return basename(dirname($_SERVER["SCRIPT_FILENAME"]));
    }
}
