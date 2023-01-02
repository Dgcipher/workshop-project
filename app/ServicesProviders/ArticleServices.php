<?php
namespace App\ServicesProviders;
use App\Http\Requests\Article\CreateArticaleRequest;
use App\Http\Requests\Article\SearchArticleRequest;
use App\Http\Requests\Article\UpdateArticaleRequest;
use \App\InterfaceProviders\ArticleRepositoryInterface;
use App\Media\Media;
use App\Models\Article;

class ArticleServices extends BaseSevice implements ArticleRepositoryInterface{
    public function __construct()
    {
        parent::__construct(Article::class);
    }





}
