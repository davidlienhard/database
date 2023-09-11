<?php declare(strict_types=1);

namespace DavidLienhard\Database;

interface ResultTypeInterface
{
    /**
     * returns the corresponding mysqli code
     *
     * @author          David Lienhard <github@lienhard.win>
     * @copyright       David Lienhard
     */
    public function toMysqli() : int;
}
