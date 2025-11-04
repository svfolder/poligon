<?php


namespace core\traits;


trait EncodingTrait
{

    /**
     * @param $string
     * @return false|string
     */
    function toUTF8($string)
    {

        if (mb_detect_encoding($string, 'UTF-8', true) === false) {
            $string = mb_convert_encoding($string, "utf-8", "windows-1251");
        }

        return $string;
    }

}