<?php

namespace Intahwebz\MBExtra{


class Functions{
    public static function load(){
    }
}

}



namespace {

// mb_string - the missing functions.
// all these functions are meant to be the mb_ equivalent of their non-multi-byte-safe versions.
// If they are not, then they have a bug. Please consider fixing and sending a pull request.

function mb_ucfirst($string) {
    $first = mb_substr($string, 0, 1);
    $remaining = mb_substr($string, 1);

    return mb_strtoupper($first).$remaining;
}

function mb_ucwords($string) {
    return mb_convert_case($string, MB_CASE_TITLE);
}

function mb_lcfirst($str) {
    return mb_strtolower(mb_substr($str,0,1)).mb_substr($str,1);
}


function mb_str_split($str, $l = 0) {
    if ($l > 0) {
        $ret = array();
        $len = mb_strlen($str, "UTF-8");
        for ($i = 0; $i < $len; $i += $l) {
            $ret[] = mb_substr($str, $i, $l, "UTF-8");
        }
        return $ret;
    }
    return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
}

function mb_strcasecmp($str1, $str2, $encoding = null) {
    if (null === $encoding) {
        $encoding = mb_internal_encoding();
    }

    return strcmp(mb_strtoupper($str1, $encoding), mb_strtoupper($str2, $encoding));
}

function mb_strrev($str){
    preg_match_all('/./us', $str, $ar);
    return join('',array_reverse($ar[0]));
}

//Taken from http://www.php.net/manual/en/function.substr-replace.php#90146
function mb_substr_replace($string, $replacement, $start, $length = null, $encoding = null)
{
    $string_length = (is_null($encoding) === true) ? mb_strlen($string) : mb_strlen($string, $encoding);

    if ($start < 0)
    {
        $start = max(0, $string_length + $start);
    }
    else if ($start > $string_length)
    {
        $start = $string_length;
    }

    if ($length < 0)
    {
        $length = max(0, $string_length - $start + $length);
    }
    else if ((is_null($length) === true) || ($length > $string_length))
    {
        $length = $string_length;
    }

    if (($start + $length) > $string_length)
    {
        $length = $string_length - $start;
    }

    if (is_null($encoding) === true)
    {
        return mb_substr($string, 0, $start) . $replacement . mb_substr($string, $start + $length, $string_length - $start - $length);
    }

    return mb_substr($string, 0, $start, $encoding) . $replacement . mb_substr($string, $start + $length, $string_length - $start - $length, $encoding);
}

function mb_wordwrap($str, $width = 75, $break = "\n", $cut = false) {
    $lines = explode($break, $str);
    foreach ($lines as &$line) {
        $line = rtrim($line);
        if (mb_strlen($line) <= $width) {
            continue;
        }
        $words = explode(' ', $line);
        $line = '';
        $actual = '';
        foreach ($words as $word) {
            if (mb_strlen($actual.$word) <= $width) {
                $actual .= $word.' ';
            }
            else {
                if ($actual != '') {
                    $line .= rtrim($actual).$break;
                }
                $actual = $word;
                if ($cut) {
                    while (mb_strlen($actual) > $width) {
                        $line .= mb_substr($actual, 0, $width).$break;
                        $actual = mb_substr($actual, $width);
                    }
                }
                $actual .= ' ';
            }
        }
        $line .= trim($actual);
    }

    return implode($break, $lines);
}

/**
 * Replace all occurrences of the search string with the replacement string.
 *
 * @author Sean Murphy <sean@iamseanmurphy.com>
 * @copyright Copyright 2012 Sean Murphy. All rights reserved.
 * @license http://creativecommons.org/publicdomain/zero/1.0/
 * @link http://php.net/manual/function.str-replace.php
 *
 * @param mixed $search
 * @param mixed $replace
 * @param mixed $subject
 * @param int $count
 * @return mixed
 */
function mb_str_replace($search, $replace, $subject, &$count = 0) {
    if (!is_array($subject)) {
        // Normalize $search and $replace so they are both arrays of the same length
        $searches = is_array($search) ? array_values($search) : array($search);
        $replacements = is_array($replace) ? array_values($replace) : array($replace);
        $replacements = array_pad($replacements, count($searches), '');

        foreach ($searches as $key => $search) {
            $parts = mb_split(preg_quote($search), $subject);
            $count += count($parts) - 1;
            $subject = implode($replacements[$key], $parts);
        }
    } else {
        // Call mb_str_replace for each subject in array, recursively
        foreach ($subject as $key => $value) {
            $subject[$key] = mb_str_replace($search, $replace, $value, $count);
        }
    }

    return $subject;
}


}
