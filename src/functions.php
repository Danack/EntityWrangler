<?php


function snakify($word)
{
    return preg_replace(
        '/(^|[a-z])([A-Z])/e',
        'strtolower(strlen("\\1") ? "\\1_\\2" : "\\2")',
        $word
    );
}

function camelize($word)
{
    return preg_replace('/(^|_)([a-z])/e', 'strtoupper("\\2")', $word);
}

function getClassName($namespaceClass)
{
    $lastSlashPosition = mb_strrpos($namespaceClass, '\\');

    if ($lastSlashPosition !== false) {
        return mb_substr($namespaceClass, $lastSlashPosition + 1);
    }

    return $namespaceClass;
}

function getNamespace($namespaceClass)
{
    if (is_object($namespaceClass)) {
        $namespaceClass = get_class($namespaceClass);
    }

    $lastSlashPosition = mb_strrpos($namespaceClass, '\\');

    if ($lastSlashPosition !== false) {
        return mb_substr($namespaceClass, 0, $lastSlashPosition);
    }

    return "\\";
}


