<?php


namespace EntityMap;

use EntityWrangler\Generator\ClassPath;

class FileWriter
{
    private $classPath;
    
    public function __construct(ClassPath $classPath)
    {
        $this->classPath = $classPath;
    }
    
    
    public function write($filename, $output)
    {
        $filepath = $this->classPath->getPath()."/".$filename;

        @mkdir($filename, 0755, true);
        $fileHandle = @fopen($filepath, "w");
        fwrite($fileHandle, $output);
        fclose($fileHandle);
    }
}
