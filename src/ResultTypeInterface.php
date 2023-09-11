<?php declare(strict_types=1);

namespace DavidLienhard\Database;

interface ResultTypeInterface
{
    public function toMysqli() : int;
}
