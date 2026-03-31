<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Resources\CategoryResource;

class CategoryController extends BaseApiController
{
    private CategoryService $categoryService;

    /*
    * コンストラクタ
    */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /*
    * GET用ルート
    */
    public function index()
    {
        // 一覧を取得
        $categories = $this->categoryService->list();
        return CategoryResource::collection($categories);
    }

    /*
    * GET用ルート（特定のID検索）
    */
    public function show($id)
    {
        //
    }

    /*
    * POST用ルート
    */
    public function store(Request $request)
    {
        return $this->categoryService->create($request->all());
    }

    /*
    * PUT/UPDATE用ルート
    */
    public function update(Request $request, $id)
    {
        //
    }

    /*
    * DELETE用ルート
    */
    public function destroy($id)
    {
        $this->categoryService->delete($id);
        return response()->json(['result' => 1], 200);
    }
}
