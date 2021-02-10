<?php
/**
 * contains parameter class for Database
 *
 * @package         tourBase
 * @subpackage      Core\Database
 * @author          David Lienhard <david@lienhard.win>
 * @copyright       tourasia
 */

declare(strict_types=1);

namespace DavidLienhard\Database;

use function in_array;
use DavidLienhard\Database\ParameterInterface;

/**
 * class to set parameters for a database query
 *
 * @author          David Lienhard <david@lienhard.win>
 * @copyright       tourasia
 */
class Parameter implements ParameterInterface
{
    /**
     * sets the parameters of this object
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @copyright       David Lienhard
     * @param           string                      $type   type of the parameter (can be i, s, d or b)
     * @param           int|float|string|bool|null  $value  value of the parameter
     * @return          void
     * @throws          \InvalidArgumentException           if given type is invalid
     * @uses            self::$type
     * @uses            self::$value
     */
    public function __construct(private string $type, private int | float | string | bool | null $value)
    {
        if (!in_array($type, [ "i", "s", "d", "b"], true)) {
            throw new \InvalidArgumentException("type must be i, s, d or b. '".$type."' given");
        }
    }

    /**
     * returns the type of this parameter
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @copyright       David Lienhard
     * @uses            self::$type
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * returns the value of this parameter
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @copyright       David Lienhard
     * @uses            self::$value
     */
    public function getValue() : int | float | string | bool | null
    {
        return $this->value;
    }
}
