<?php
namespace App\Repository;

class BaseRepository
{
    protected  $model;

    public function __construct($model)
    {
        $this->model=$model;
    }

    public function search()
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

    public function getById(int $id, array $attribute=['*'])
    {
        return $this->model::find($id,$attribute);
    }

    public function paginate($select, $per_page, $page)
    {
        return $this->model::simplePaginate($per_page,$select,'page',$page);
    }

    public function delete(int $id): bool
    {
        $Model=$this->getById($id);

        if(!$Model)
            return false;

        return $Model->delete();
    }
}
