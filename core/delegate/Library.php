<?php


namespace core\delegate;


class Library
{

    /**
     * @var Delegate_Iff
     */
    static private $_iff = null;


    /**
     * @return Delegate_Iff
     */
    public static function iff() {

        if( !(self::$_iff instanceof Delegate_Iff ) ) {
            self::$_iff = new Delegate_Iff();
        }
        return self::$_iff;

    }



}