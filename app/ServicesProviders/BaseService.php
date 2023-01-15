<?php
namespace App\ServicesProviders;


use App\InterfaceProviders\BaseRepositoryInterface;

class BaseSevice implements BaseRepositoryInterface {
    protected  $model;
    public function __construct($model)
    {
        $this->model=$model;
    }
    public function search($data)
    {
        return $this->model::all();
    }
    public function create(array $data): bool
    {
        return $this->model::create($data)->exists;
    }
    public function update(array $data, int $id): bool
    {
        return $this->model::whereId($id)->update($data);
    }
    public function getById($id): mixed
    {
        return $this->model::find($id);
    }
    public function paginate($select, $per_page, $page)
    {
        return $this->model::simplePaginate($per_page,$select,'page',$page);
    }
    public function delete($id): bool
    {
        $Model=$this->getById($id);
        if(!$Model)
            return false;
        return $Model->delete();
    }

}
