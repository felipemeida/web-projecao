<?php

namespace Agencia\Close\Services\Filter;

use Agencia\Close\Models\Filter\Automaker;

class FilterAutomakers extends FilterSidebar
{
    private Automaker $automaker;

    public function verifyInputs()
    {
        if (!empty($_GET['catalogs'])) {
            $this->automaker->andWhereIn('p.catalogo',  $_GET['catalogs']);
        }
        $this->addAdditional();
//        if (!empty($_GET['ate'])) {
//            $this->automaker->andWhere('pa.montadora', $_GET['m']);
//        }
    }

    public function get(): array
    {
        $this->automaker = new Automaker();
        $this->verifyInputs();
        $result = $this->automaker->getAutomaker($this->idCompany);
        if ($result->getResult()) {
            return $result->getResult();
        } else {
            return [];
        }
    }

    private function addAdditional()
    {
        foreach ($this->additionalWhere as $additional) {
            $this->automaker->andWhere($additional['field'], $additional['value'], $additional['symbol']);
        }
        $this->automaker->where($this->completeWhere);
    }
}