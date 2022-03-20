<?php

namespace App\Models\Enums;

class Job extends AbstractEnum
{

    public const __default = 0;

    public const UNDEFINED = self::__default;

    public const TILER = 1;

    public const CARPENTER = 2;

    public const ELECTRICIAN = 3;

    public const MASON = 4;

    public const CRANE_OPERATOR = 5;

    public const PAINTER = 6;

    public const PLUMBER = 7;

    public const ENGINE_DRIVER = 8;

    public const ROPE_ACCESS = 9;

    public const WORKER = 10;

}
