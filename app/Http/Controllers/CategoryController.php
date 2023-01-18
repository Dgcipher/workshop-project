<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Methods\Helper;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use Helper;
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        //
        $data = $request->all();
        Category::create($data);
        return $this->responseJson(1,'data added successfully');
    }

  }
