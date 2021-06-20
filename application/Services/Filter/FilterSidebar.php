<?php

namespace Agencia\Close\Services\Filter;

abstract class FilterSidebar
{
    protected string $idCompany;
    protected array $additionalWhere;
    protected string $completeWhere = '';

    public function __construct($idCompany)
    {
        $this->idCompany = $idCompany;
    }

    public function setAdditionalWhere(array $additionalWhere)
    {
        $this->additionalWhere = $additionalWhere;
    }

    public function addCompleteWhere(string $where)
    {
        $this->completeWhere .= $where;
    }

    abstract public function get(): array;
}