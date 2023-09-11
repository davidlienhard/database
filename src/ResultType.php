<?php declare(strict_types=1);

namespace DavidLienhard\Database;

use DavidLienhard\Database\ResultTypeInterface;

enum ResultType implements ResultTypeInterface
{
    case assoc;
    case num;
    case both;

    /**
     * returns the corresponding mysqli code
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function toMysqli() : int
    {
        return match ($this) {
            self::assoc => MYSQLI_ASSOC,
            self::num => MYSQLI_NUM,
            self::both => MYSQLI_BOTH
        };
    }
}
