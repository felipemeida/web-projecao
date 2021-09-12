<?php

namespace Felmework\Adapters;

use Felmework\Helpers\Result;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Stream;

class HttpAdapter
{
    private $header;
    private $query;
    private $result;
    private $response;

    public function __construct()
    {
        $this->result = new Result();
    }

    public function setHeader(array $header)
    {
        $this->header = $header;
    }

    public function setQuery(array $query)
    {
        $this->query = $query;
    }

    public function doRequest($method, $baseUrl): Result
    {
        $client = new Client();
        try {
            $this->response = $client->request($method, $baseUrl,
                [
                    'header' => $this->header,
                    'query' => $this->query,
                ]
            );
            $this->setResult();
        } catch (GuzzleException $e) {
            $this->result->setError(true);
            $this->result->setMessage('Problema na requisição do Guzzle');
            $this->result->setInfo(['exception' => $e->getMessage()]);
        } finally {
            return $this->getResult();
        }
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function getBody()
    {
        return json_decode($this->response->getBody()->read(2000000));
    }

    public function getContent(){
        return $this->response->getContent();
    }

    private function setResult(){
        if ($this->getStatusCode() >= 300) {
            $this->result->setError(true);
            $this->result->setMessage('Requisição não foi completada');
        } else {
            $this->result->setError(false);
            $this->result->setMessage('Requisição realizada');
        }
        $this->result->setInfo([
            'body' => $this->getBody(),
            'code' => $this->getStatusCode(),
        ]);
    }

    public function getResult(): Result
    {
        return $this->result;
    }
}