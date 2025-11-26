<?php declare(strict_types=1);

/**
 * contains parameter class for Database
 *
 * @author          David Lienhard <github@lienhard.win>
 * @copyright       David Lienhard
 */

namespace DavidLienhard\Database;

use DavidLienhard\Database\ParameterInterface;
use function in_array;

/**
 * class to set parameters for a database query
 *
 * @author          David Lienhard <github@lienhard.win>
 * @copyright       David Lienhard
 */
class Parameter implements ParameterInterface
{
    /**
     * sets the parameters of this object
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     * @param           string                      $type   type of the parameter (can be i, s, d or b)
     * @param           int|float|string|bool|null  $value  value of the parameter
     * @return          void
     * @throws          \InvalidArgumentException           if given type is invalid
     */
    public function __construct(private string $type, private int|float|string|bool|null $value)
    {
        if (!in_array($type, [ "i", "s", "d", "b"], true)) {
            throw new \InvalidArgumentException("type must be i, s, d or b. '".$type."' given");
        }
    }

    /**
     * returns the type of this parameter
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * returns the value of this parameter
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function getValue() : int|float|string|bool|null
    {
        return $this->value;
    }
}
