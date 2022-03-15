<?php


namespace App\Models\Enums;

use UnexpectedValueException;

abstract class SplEnum
{
    /**
     * @constant(__default) The default value of the enum.
     */
    public const __default = null;

    /**
     * The current value of the enum.
     *
     * @var mixed
     */
    protected $value;

    /**
     * splEnum constructor.
     *
     * @param mixed $value The initial value of the enum.
     * @throws UnexpectedValueException
     */
    public function __construct($value = null)
    {
        $ref = new \ReflectionClass($this);

        if (!in_array($value, $ref->getConstants())) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . static::class);
        }

        $this->value = $value;
    }

    /**
     * Get a list of all the constants in the enum
     *
     * @param  boolean $include_default Whether to include the default value in the list or no.
     *
     * @return array                    The list of constants defined in the enum.
     */
    public static function getConstList($include_default = false)
    {
        $reflected = new \ReflectionClass(new static(null));

        $constants = $reflected->getConstants();

        if (! $include_default) {
            unset($constants['__default']);
            return $constants;
        }

        return $constants;
    }

    /**
     * The string representation of the enum.
     *
     * @return string The current value of the enum.
     */
    final public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * The string representation of the enum.
     *
     * @return int The current value of the enum.
     */
    final public function __toInt(): int
    {
        return (int)$this->value;
    }
}
