<?php

namespace Agencia\Close\Services\Filter;

use Agencia\Close\Models\Filter\Category;

class FilterCategories extends FilterSidebar
{
    private Category $categories;

    public function verifyInputs()
    {
        if (!empty($_GET['m'])) {
            $this->categories->andWhereIn('montadora', $_GET['m']);
        }
        if (!empty($_GET['mo'])) {
            $this->categories->andWhereIn('modelo', $_GET['mo']);
        }
        if (!empty($_GET['de'])) {
            $this->categories->andWhere('anos', '%' . $_GET['de'] . '%', 'LIKE');
        }
        if (!empty($_GET['ate'])) {
            $this->categories->andWhere('anos', '%' . $_GET['ate'] . '%', 'LIKE');
        }
        if (!empty($_GET['catalogs'])) {
            $this->categories->andWhereIn('p.catalogo',  $_GET['catalogs']);
        }
        $this->addAdditional();
    }

    public function get(): array
    {
        $this->categories = new Category();
        $this->verifyInputs();
        $result = $this->categories->getCategory($this->idCompany);
        if ($result->getResult()) {
            return $this->buildTree($result->getResult());
        } else {
            return [];
        }
    }

    public function buildTree($categories, $parentId = 0): array
    {
        $branch = array();

        foreach ($categories as $item) {
            if ($item['parent'] == $parentId) {
                $children = $this->buildTree($categories, $item['id_categoria']);
                if ($children) {
                    $item['children'] = $children;
                }
                $branch[] = $item;
            }
        }
        return $branch;

    }

    private function addAdditional()
    {
        foreach ($this->additionalWhere as $additional) {
            $this->categories->andWhere($additional['field'], $additional['value'], $additional['symbol']);
        }
        $this->categories->where($this->completeWhere);
    }
}