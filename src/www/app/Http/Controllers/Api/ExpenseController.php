<?php

namespace App\Http\Controllers\Api;

use App\Services\ExpenseService;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends BaseApiController
{
    private ExpenseService $expenseService;

    /*
    * コンストラクタ
    */
    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /*
    * GET用ルート
    */
    public function index(Request $request)
    {
        // 一覧を取得
        $expenses = $this->expenseService->getMonthlySummary(
            $request->input('month')
        );
        return ExpenseResource::collection($expenses);
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
        return $this->expenseService->create($request->all());
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
    public function destroy(Expense $expense)
    {
        $this->expenseService->delete($expense);
        return response()->json(['result' => 1], 200);
    }
}
