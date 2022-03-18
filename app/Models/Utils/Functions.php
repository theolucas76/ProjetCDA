<?php

namespace App\Models\Utils;

use DateTime;

class Functions
{
    public static function fromUnix( int $value ): ?\DateTime {
        $myDateTime = new DateTime();
        $myDateTime = $myDateTime->setTimestamp( $value );
        if ($myDateTime != false) {
            return $myDateTime;
        }
        return null;
    }
}
