<?php
namespace App\ServicesProviders;
use App\Http\Requests\Article\CreateArticaleRequest;
use App\Http\Requests\Article\SearchArticleRequest;
use App\Http\Requests\Article\UpdateArticaleRequest;
use \App\InterfaceProviders\ArticleServicesInterface;
use App\Repository\ArticleRepository;
use app\Traits\ApiRespone;

class ArticleServices implements ArticleServicesInterface
{
    use ApiRespone;

    private ArticleRepository $articleRepository;
    private FilesService $filesService;
    public function __construct(ArticleRepository $articleRepository , FilesService $filesService)
    {
        $this->articleRepository=$articleRepository;
        $this->filesService=$filesService;
    }

    public function search(SearchArticleRequest $request):object
    {
        $select=$request->input('select',['*']);
        $per_page=$request->input('per_page',10);
        $page=$request->input('page',1);
        return $this->articleRepository->paginate($select,$per_page,$page);
    }

    public function createArticle(CreateArticaleRequest $request):bool
    {
        $data = $request->except('image');

        if($request->hasFile('image'))
            $data['image'] = $this->filesService->uploadFile($request->image);

        return $this->articleRepository->create($data);

    }

    public function updateArticle(UpdateArticaleRequest $request , int $id):bool
    {
        $data = $request->except('image');

        if($request->hasFile('image'))
        {
            $data['image'] = $this->filesService->uploadFile($request->image);
            $article= $this->articleRepository->getById($id,['image']);
            $this->filesService->deleteFile($article->image);
        }

        return $this->articleRepository->update($data,$id);
    }

    public function deleteArticle(int $id):bool
    {
       $article= $this->articleRepository->getById($id,['image']);

       if ($article->image && $this->filesService->deleteFile($article->image))
           return $this->articleRepository->delete($id);
       return false;
    }

    public function showArticle(int $id):mixed
    {
        return $this->articleRepository->getById($id);
    }


}
