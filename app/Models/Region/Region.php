<?php

namespace Felmework\Models\Region;

use Felmework\Conn\Create;
use Felmework\Conn\Delete;
use Felmework\Conn\Read;
use Felmework\Conn\Update;
use Felmework\Models\Model;

class Region extends Model {
    private string $table = 'regiao';

    public function list(): Read
    {
        $read = new Read();
        $read->FullRead('SELECT * FROM ' . $this->table);
        return $read;
    }

    public function save(array $data): Create
    {
        $create = new Create();
        $create->ExeCreate($this->table, $data);
        return $create;
    }

    public function update($id, array $data): Update
    {
        $update = new Update();
        $update->ExeUpdate($this->table, $data, 'WHERE id_regiao = :id', "id={$id}");
        return $update;
    }

    public function delete($id): Delete
    {
        $delete = new Delete();
        $delete->ExeDelete($this->table, 'WHERE id_regiao = :id', "id={$id}");
        return $delete;
    }

    public function show($id): Read
    {
        $read = new Read();
        $read->ExeRead($this->table, 'WHERE id_regiao = :id', "id={$id}");
        return $read;
    }
}