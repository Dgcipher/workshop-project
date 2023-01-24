<?php

namespace App\Http\Repositories;

use App\Models\Post;
class PostRepository
{
    private $postModel;
    public function __construct(Post $post)
    {
        $this->postModel = $post;
    }
    public function search($per_page, $select, $page)
    {
      return  $this->postModel::simplePaginate($per_page, $select, 'page', $page);
    }
    public function create($data)
    {
      return  $this->postModel::create($data);
    }
    public function findOrFail($id)
    {
        return $this->postModel::findOrFail($id);
    }
    public function read($id)
    {
       return $this->findOrFail($id);
    }
    public function update($data, $id)
    {
       return $this->findOrFail($id)->update($data);
    }
    public function delete($id)
    {
       return $this->findOrFail($id);
    }
}
