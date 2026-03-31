<?php

namespace App\Http\Controllers\Api;

use App\Services\PaymentMethodService;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentMethodResource;

class PaymentMethodController extends BaseApiController
{
    private PaymentMethodService $paymentMethodService;

    /*
    * コンストラクタ
    */
    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    /*
    * GET用ルート
    */
    public function index()
    {
        // 一覧を取得
        $categories = $this->paymentMethodService->list();
        return PaymentMethodResource::collection($categories);
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
        return $this->paymentMethodService->create($request->all());
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
        return $this->paymentMethodService->delete($id);
    }
}
