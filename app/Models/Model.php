<?php

namespace Felmework\Models;

use Felmework\Conn\Create;
use Felmework\Conn\Read;
use Felmework\Conn\Update;
use Felmework\Conn\Delete;
use Felmework\Helpers\String\Strings;

abstract class Model
{
    protected Create $create;
    protected Read $read;
    protected Update $update;
    protected Delete $delete;
    protected string $where = '';
    protected string $join = '';

    protected function offset(int $page, int $limit): int
    {
        return ($page - 1) * $limit;
    }

    protected function pages(int $number, int $limit): int
    {
        return ceil($number / $limit);
    }

    public function andWhere($field, $value, $symbol = '=')
    {
        $this->where .= " AND " . $field . " " . $symbol . " '" . $value . "' ";
    }

    public function andWhereIn($field, $value)
    {
        $this->where .= " AND " . $field . " IN " .  Strings::convertCommaForFormatToInSQl($value);
    }

    public function where(string $where)
    {
        $this->where .= $where;
    }

    public function join(string $join) {
        $this->join .= $join;
    }
}