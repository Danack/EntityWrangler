<?php

namespace EntityWrangler\Generator;

class ClassPath
{
    private $path;

    public function __construct($path)
    {
        if ($path === null) {
            throw new \LogicException(
                "Path cannot be null for ClassPath."
            );
        }
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }
}
