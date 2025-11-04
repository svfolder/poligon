<?php

namespace core\delegate;


class Delegate
{


    /**
     * Хранилище библиотеки кода
     * @var Library
     */
    static private $_lib = null;


    public function __construct()
    {

    }

    /**
     * @return Library
     */
    public static function lib()
    {

        if (!(self::$_lib instanceof Library)) {
            self::$_lib = new Library();
        }
        return self::$_lib;
    }

}

?>