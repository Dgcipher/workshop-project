<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\CreateArticaleRequest;
use App\Http\Requests\Article\SearchArticleRequest;
use App\Http\Requests\Article\UpdateArticaleRequest;
use App\InterfaceProviders\ArticleServicesInterface;
use App\ServicesProviders\ArticleServices;
use App\ServicesProviders\FilesService;
use App\Traits\ApiRespone;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ArticleController extends Controller
{
use ApiRespone;

    private ArticleServicesInterface $articleService;
    private FilesService $filesService;

    public function __construct(ArticleServicesInterface $articleService , FilesService $fileService)
    {
        $this->articleService=$articleService;
        $this->filesService=$fileService;
    }

    public function search(SearchArticleRequest $request) :JsonResponse
    {
            return $this->sendData('',$this->articleService->search($request));
    }

    public function create(CreateArticaleRequest $request):JsonResponse
    {
            if($this->articleService->createArticle($request))
                return $this->success("Article Created Successfully");

            return $this->error('Article Not Created',Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function update(UpdateArticaleRequest $request, int $id) :JsonResponse
    {
        if(!$this->articleService->updateArticle($request,$id))
            return $this->error('Article Not Updated',Response::HTTP_INTERNAL_SERVER_ERROR);

        return $this->success('Article Updated Successfully');
    }

    public function edit( int $id):JsonResponse
    {
            $article = $this->articleService->showArticle($id);
            if($article)
                return $this->sendData('',$article);

            return $this->error('Article Not Found',Response::HTTP_NOT_FOUND);
    }

    public function delete($id):JsonResponse
    {
            if($this->articleService->deleteArticle($id))
                return $this->success('Article Deleted Successfully');

            return $this->error('Article Not Found',Response::HTTP_NOT_FOUND);
    }

    }
