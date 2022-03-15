<?php

namespace App\Models\Enums;

class AbstractEnum extends SplEnum
{
    /**
     * Test if enum is default value
     * @return bool
     */
    public function isDefault() : bool {
        return $this->__toString() === (string)null || $this === static::__default;
    }

    /**
     * Test if key exists
     * @param $key
     * @return bool
     */
    public static function hasKey( $key ) : bool {
        try {
            $myEnumClassName = static::class;
            new $myEnumClassName($key);
            return true;
        } catch( \Exception $e ) {
            return false;
        }
    }

    /**
     * Get enum value from key
     * @param $key
     * @return static|null
     */
    public static function get( $key ) : ?self {
        if( static::hasKey( $key ) ) {
            return new static( $key );
        }
        return null;
    }

}
