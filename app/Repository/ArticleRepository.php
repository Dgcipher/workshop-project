<?php
namespace App\Repository;

use App\Models\Article;

class ArticleRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Article::class);
    }
}
