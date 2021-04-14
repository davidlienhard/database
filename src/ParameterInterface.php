<?php
/**
 * contains parameter interface for Database
 *
 * @package         tourBase
 * @subpackage      Core\Database
 * @author          David Lienhard <david@lienhard.win>
 * @copyright       tourasia
 */

declare(strict_types=1);

namespace DavidLienhard\Database;

/**
 * interface to set parameters for a database query
 *
 * @author          David Lienhard <david@lienhard.win>
 * @copyright       tourasia
 */
interface ParameterInterface
{
    /**
     * sets the parameters of this object
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @copyright       David Lienhard
     * @param           string                      $type   type of the parameter (can be i, s, d or b)
     * @param           int|float|string|bool|null  $value  value of the parameter
     * @return          void
     */
    public function __construct(
        string $type,
        int|float|string|bool|null $value
    );

    /**
     * returns the type of this parameter
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @copyright       David Lienhard
     */
    public function getType() : string;

    /**
     * returns the value of this parameter
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @copyright       David Lienhard
     */
    public function getValue() : int|float|string|bool|null;
}
