<?php

namespace Agencia\Close\Services\Filter;

use Agencia\Close\Models\Filter\Features;

class FilterFeatures extends FilterSidebar
{
    private Features $features;

    public function verifyInputs()
    {
        $this->addAdditional();
    }

    public function get(): array
    {
        $versao = $this->getFeaturesList('versao');
        $motor = $this->getFeaturesList('motor');
        $direcao = $this->getFeaturesList('direcao');
        $transmissao = $this->getFeaturesList('transmissao');
        $diferencial = $this->getFeaturesList('diferencial');
        $combustivel = $this->getFeaturesList('combustivel');

        return [
            'versao' => $versao,
            'motor' => $motor,
            'direcao' => $direcao,
            'transmissao' => $transmissao,
            'diferencial' => $diferencial,
            'combustivel' => $combustivel
        ];
    }

    public function getFeaturesList($featureSlug): array
    {
        $this->features = new Features();
        $this->verifyInputs();
        $result = $this->features->getFeatures($this->idCompany, $featureSlug);
        if ($result->getResult()) {
            return $this->featuresClear($result->getResult(), $featureSlug);
        } else {
            return [];
        }
    }

    private function featuresClear($features, $featureSlug): array
    {
        $featuresExploded = [];
        foreach($features as $feature){
            $featuresExploded = array_merge($featuresExploded, explode('|', $feature[$featureSlug]));
        }
        return array_unique($featuresExploded);
    }

    private function addAdditional()
    {
        foreach ($this->additionalWhere as $additional) {
            $this->features->andWhere($additional['field'], $additional['value'], $additional['symbol']);
        }
        $this->features->where($this->completeWhere);
    }
}