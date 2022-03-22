<?php

namespace App\Models\Enums;

class MaterialCategory extends AbstractEnum
{
    public const __default = 0;

    public const UNDEFINED = self::__default;

    public const TOOLS = 1;

    public const CONSTRUCTION_MACHINE = 2;

    public const CAR = 3;

    public const PAINTINGS = 4;

    public const ELECTRICAL_EQUIPMENT = 5;

    public const PLUMBINGS_EQUIPMENT = 6;

    public const CARPENTRY_EQUIPMENT = 7;

}
