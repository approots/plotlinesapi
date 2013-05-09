<?php
namespace utilities;

class Text
{
    public static function toAscii($text, $delimiter='-')
    {
        if ($text)
        {
            $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
            $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
            $clean = strtolower(trim($clean, '-'));
            $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

            return $clean;
        }

        return $text;
    }
}