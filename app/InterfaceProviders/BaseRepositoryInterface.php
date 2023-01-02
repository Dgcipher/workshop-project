<?php
namespace App\InterfaceProviders;



interface BaseRepositoryInterface{
public function search($data);
public function update( array $data, int $id):bool;
public function create (array $data):bool;
public function getById(int $id):mixed;
public function delete(int $id):bool;
public function paginate($select,$per_page,$page);
}
