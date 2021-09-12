<?php

namespace Felmework\Controllers\Region;

use Felmework\Controllers\Controller;
use Felmework\Models\Region\Region;

class RegionController extends Controller
{
    public function index(array $params)
    {
        $this->setParams($params);
        $this->regionList();
    }

    public function regionList($message = '')
    {
        $region = new Region();
        $regionList = $region->list();

        if ($regionList->getResult()) {
            $this->render('pages/region/index.twig', ['message' => $message, 'regions' => $regionList->getResult()]);
        } else {
            $this->render('pages/error/error.twig', ['message' => 'Regiões não encontradas!']);
        }
    }

    public function create(array $params)
    {
        $this->setParams($params);
        $this->render('pages/region/form.twig', []);
    }

    public function store(array $params)
    {
        $this->setParams($params);
        $validation = $this->validateRegion();
        if ($validation) {
            $this->render('pages/region/form.twig', array_merge($params, ['alert_no_regiao' => $validation]));
            return;
        }
        $region = new Region();
        $region->save($this->params);
        $this->regionList('Região cadastrada com sucesso!');
    }

    public function delete(array $params)
    {
        $this->setParams($params);
        $region = new Region();
        $region->delete($this->params['id']);
        $this->regionList('Região excluída com sucesso!');
    }

    public function change(array $params)
    {
        $this->setParams($params);
        $region = new Region();
        $regionResult = $region->show($params['id']);
        $this->render('pages/region/form.twig', array_merge(['id' => $params['id']], $regionResult->getResult()[0]));
    }

    public function update(array $params)
    {
        $this->setParams($params);
        $validation = $this->validateRegion();
        if ($validation) {
            $this->render('pages/region/form.twig', array_merge($params, ['alert_no_regiao' => $validation]));
            return;
        }
        $region = new Region();
        $regionResult = $region->update($this->params['id'], ['no_regiao' => $this->params['no_regiao']]);
        $this->regionList('Região alterada com sucesso!');
    }

    function validateRegion() {
        if ($this->params['no_regiao'] === '') {
            return 'O nome da região precisa ser preenchido';
        }
        if (strlen($this->params['no_regiao']) >= 15) {
            return 'O nome da região deve ter até 15 dígitos';
        }
        return false;
    }
}