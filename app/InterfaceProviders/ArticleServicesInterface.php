<?php
namespace App\InterfaceProviders;

use App\Http\Requests\Article\CreateArticaleRequest;
use App\Http\Requests\Article\SearchArticleRequest;
use App\Http\Requests\Article\UpdateArticaleRequest;

interface ArticleServicesInterface
{
    public function search(SearchArticleRequest $request);

    public function updateArticle( UpdateArticaleRequest $request , int $id):bool;

    public function createArticle(CreateArticaleRequest $request):bool;

    public function showArticle(int $id):mixed;

    public function deleteArticle(int $id):bool;


}
