<?php


namespace EntityWrangler;

use EntityWrangler\EntityWranglerException;

class SavePath
{
    private $path;

    public function __construct($path)
    {
        if ($path === null) {
            throw new EntityWranglerException(
                "Path cannot be null for SavePath"
            );
        }
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }
}
