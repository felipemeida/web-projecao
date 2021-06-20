<?php

namespace Agencia\Close\Services\Filter;

use Agencia\Close\Helpers\String\Strings;
use CodeInc\StripAccents\StripAccents;

class FilterCollection
{
    private string $idCompany;
    protected array $additionalWhere = [];
    protected string $actionFilter;

    public function __construct()
    {
        $this->actionFilter($_SESSION['slug-company']);
    }

    public function setIdCompany($idCompany)
    {
        $this->idCompany = $idCompany;
    }

    public function getSearchWhere(): string
    {
        $where = '';
        if(!empty($_GET['s'])){
            $where .= ' AND ';
            $term = explode(' ', Strings::removePreposition($_GET['s']));
            for ($i=0; $i < count($term); $i++) {
                if($term[$i] !== '' || $term[$i] !== ' '){
                    $where .= "(p.`titulo` LIKE '%".$term[$i]."%' OR p.`codigo` LIKE '%".$term[$i]."%' OR p.`categoria` LIKE '%".$term[$i]."%' OR p.`termos_clear` LIKE '%".$term[$i]."%'  OR p.`termos_clear` LIKE '%".StripAccents::strip($term[$i])."%' OR p.`termos_aplicacoes` LIKE '% ".$term[$i]." %' OR p.`equivalencia` LIKE '%".$term[$i]."%' OR p.`concorrentecodigos` LIKE '%".$term[$i]."%' OR p.`tags` LIKE '%".$term[$i]."%') AND ";
                }
            }
            $where = substr($where, 0, -4);
        }
        return $where;
    }

    public function get(): array
    {
        $searchWhere = $this->getSearchWhere();

        $automakers = new FilterAutomakers($this->idCompany);
        $automakers->setAdditionalWhere($this->additionalWhere);
        $automakers->addCompleteWhere($searchWhere);

        $models = new FilterModels($this->idCompany);
        $models->setAdditionalWhere($this->additionalWhere);
        $models->addCompleteWhere($searchWhere);

        $categories = new FilterCategories($this->idCompany);
        $categories->setAdditionalWhere($this->additionalWhere);
        $categories->addCompleteWhere($searchWhere);

        $features = new FilterFeatures($this->idCompany);
        $features->setAdditionalWhere($this->additionalWhere);
        $features->addCompleteWhere($searchWhere);

        return [
            'automakers' => $automakers->get(),
            'models' => (!empty($_GET['m'])) ? $models->get() : [],
            //'years' => $years->get(),
            'categories' => $categories->get(),
            'features' => $features->get(),
            'actionFilter' => $this->actionFilter
        ];
    }

    public function actionFilter($slugCompany)
    {
        $this->actionFilter = $this->actionListURL($slugCompany);
    }

    public function actionListURL($slugCompany): string
    {
        $url = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $actionEmpty = array('produtos', 'lancamentos', 'lista', 'catalogo-impresso', 'manuais');
        if (!in_array(end($url), $actionEmpty)) {
            $action = DOMAIN . "/" . $slugCompany . "/produtos";
        } else {
            $action = "";
        }
        return $action;
    }

    public function additionalWhere($field, $value, $symbol = '=')
    {
        array_push($this->additionalWhere, ['field' => $field, 'value' => $value, 'symbol' => $symbol]);
    }
}