<?php

namespace Agencia\Close\Services\Filter;

use Agencia\Close\Models\Filter\FilterModel;

class FilterModels extends FilterSidebar
{
    private FilterModel $model;

    public function verifyInputs()
    {
        if (!empty($_GET['catalogs'])) {
            $this->model->andWhereIn('p.catalogo',  $_GET['catalogs']);
        }
        if (!empty($_GET['m'])) {
            $this->model->andWhereIn('pa.montadora', $_GET['m']);
        }
        $this->addAdditional();
    }

    public function get(): array
    {
        $this->model = new FilterModel();
        $this->verifyInputs();
        $result = $this->model->getModel($this->idCompany);
        if ($result->getResult()) {
            return $result->getResult();
        } else {
            return [];
        }
    }

    private function addAdditional()
    {
        foreach ($this->additionalWhere as $additional) {
            $this->model->andWhere($additional['field'], $additional['value'], $additional['symbol']);
        }
        $this->model->where($this->completeWhere);
    }
}