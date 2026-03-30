<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Request;
use App\Services\ExpenseService;

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
    public function index()
    {
        return $this->expenseService->getMonthlySummary(null);
    }

    /*
    * GET用ルート（特定のID検索）
    */
    public function show($id)
    {
        //
    }

    /*
    * GET用ルート（特定の月検索）
    */
    public function monthlySummary(Request $request)
    {
        return $this->expenseService->getMonthlySummary(
            $request->query('month')
        );
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
    public function destroy($id)
    {
        return $this->expenseService->delete($id);
    }
}
