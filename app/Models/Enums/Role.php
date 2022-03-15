<?php

namespace App\Models\Enums;

class Role extends AbstractEnum
{
    public const __default = 0;

    public const UNDEFINED = self::__default;

    public const DIRECTOR = 1;

    public const MANAGER = 2;

    public const EMPLOYEE = 3;

    public const CUSTOMER = 4;

}
