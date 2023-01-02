<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\CreateArticaleRequest;
use App\Http\Requests\Article\SearchArticleRequest;
use App\Http\Requests\Article\UpdateArticaleRequest;
use App\InterfaceProviders\ArticleRepositoryInterface;
use App\InterfaceProviders\FilesInterFace;
use App\Traits\ApiRespone;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ArticleController extends Controller
{
use ApiRespone;
    private ArticleRepositoryInterface $articleRepository;
    private FilesInterFace $filesService;
    public function __construct(ArticleRepositoryInterface $articleRepository ,FilesInterFace $fileService){
        $this->articleRepository=$articleRepository;
        $this->filesService=$fileService;
    }
public function search(SearchArticleRequest $request) :JsonResponse{
        $select=$request->input('select',['*']);
        $per_page=$request->input('per_page',10);
        $page=$request->input('page',1);
        $data =$this->articleRepository->paginate($select,$per_page,$page);
        return $this->SendData('',$data);
}
public function create(CreateArticaleRequest $request):JsonResponse{
        $data = $request->except('_token','image');
    if($request->hasFile('image')){
      $data['image'] = $this->filesService->uploadFile($request->image,public_path('images\\articles'));
    }
        if($this->articleRepository->create($data)) {
            return $this->success("Article Created Successfully");
        }
        return $this->error('Article Not Created',Response::HTTP_INTERNAL_SERVER_ERROR);
}
public function update(UpdateArticaleRequest $request,$id) :JsonResponse{
    $data = $request->except('_token','image');
    if($request->hasFile('image')){
        $data['image'] = $this->filesService->uploadFile($request->image,public_path('images\\articles'));
        $this->filesService->deleteFile(public_path("images\\articles\\$request->image"));
    }
    $article = $this->articleRepository->update($data,$id);
    if(!$article){
        return $this->error('Article Not Updated',Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    return $this->success('Article Updated Successfully');

}
public function edit($id):JsonResponse{
        $article = $this->articleRepository->getById($id);
        if($article){
            return $this->SendData('',$article);
        }
        return $this->error('Article Not Found',Response::HTTP_NOT_FOUND);
}
public function delete($id):JsonResponse{
        if($this->articleRepository->delete($id)){
            return $this->success('Article Deleted Successfully');
        }
        return $this->error('Article Not Found',Response::HTTP_NOT_FOUND);
}
}
