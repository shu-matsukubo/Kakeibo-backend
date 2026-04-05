<?php

namespace App\Services\Expenses;

use App\Models\Expenses\Expense;
use Illuminate\Support\Facades\DB;
use App\Models\Expenses\ExpenseRecurringAdjustment;
use App\Http\Resources\Expenses\ExpenseResource;
use App\Http\Resources\Expenses\SummaryResource;
use App\Support\DateUtil;
use App\Enums\Expenses\ExpenseGroupBy;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Expenses\ExpenseCategory;
use App\Models\Expenses\ExpensePaymentMethod;

class ExpenseService
{
    /**
     * モードに応じて支出データを取得
     */
    public function getExpensesByMode(string $mode, array $params): AnonymousResourceCollection
    {
        return match ($mode) {
            'summary' => $this->getSummary($params),
            default   => $this->getHistory($params),
        };
    }

    /**
     * カテゴリごとの集計データを取得
     */
    private function getSummary(array $params): AnonymousResourceCollection
    {
        $range = $params['month']
            ? DateUtil::monthRange($params['month'])
            : DateUtil::currentMonthRange();

        $groupBy = ExpenseGroupBy::from($params['group_by'] ?? null);

        $result = match ($groupBy) {
            ExpenseGroupBy::CATEGORY => $this->aggregateByCategory($range),
            ExpenseGroupBy::PAYMENT_METHOD => $this->aggregateByPaymentMethod($range),
            ExpenseGroupBy::DATE => $this->aggregateByDate($range),
            default => collect(),
        };

        $targetMonth = $params['month'] ?? now()->format('Y-m');
        $target = \Carbon\Carbon::parse($targetMonth)->startOfMonth();

        $recurringList = ExpenseRecurringAdjustment::query()
            ->when($groupBy === ExpenseGroupBy::CATEGORY, function ($q) use ($result) {
                $q->whereIn('category_id', $result->pluck('category_id')->filter());
            })
            ->when($groupBy === ExpenseGroupBy::PAYMENT_METHOD, function ($q) use ($result) {
                $q->whereIn('payment_method_id', $result->pluck('payment_method_id')->filter());
            })
            ->whereDate('start_month', '<=', $target)
            ->whereRaw(
                'MOD(TIMESTAMPDIFF(MONTH, start_month, ?), interval_months) = 0',
                [$target->format('Y-m-01')]
            )
            ->get();

        $grouped = $recurringList->groupBy(function ($r) use ($groupBy) {
            return match ($groupBy) {
                ExpenseGroupBy::CATEGORY => $r->category_id,
                ExpenseGroupBy::PAYMENT_METHOD => $r->payment_method_id,
                default => null,
            };
        });

        $result->transform(function ($item) use ($grouped, $groupBy) {

            $key = match ($groupBy) {
                ExpenseGroupBy::CATEGORY => $item->category_id,
                ExpenseGroupBy::PAYMENT_METHOD => $item->payment_method_id,
                default => null,
            };

            $extra = collect($grouped[$key] ?? [])->sum('amount');

            $item->initial_balance = ($item->initial_balance ?? 0) + $extra;

            return $item;
        });

        return SummaryResource::collection($result);
    }

    /*
    * 支出履歴を取得
    */
    private function getHistory(array $params): AnonymousResourceCollection
    {
        // 範囲を指定
        $range = $params['month'] ? DateUtil::monthRange($params['month']) : DateUtil::currentMonthRange();

        // 取得
        $result = Expense::whereBetween('date', [
            $range['start'],
            $range['end']
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        return ExpenseResource::collection($result);
    }

    /*
    * 支出を作成
    */
    public function create(array $data)
    {
        return Expense::create($data);
    }

    /*
    * 支出を削除
    */
    public function delete(Expense $expense)
    {
        return $expense->delete();
    }

    /*
    * カテゴリごとの支出を取得
    */
    private function aggregateByCategory(array $range)
    {
        return ExpenseCategory::query()
            ->leftJoin('expenses', function ($join) use ($range) {
                $join->on('expenses.category_id', '=', 'expense_categories.id')
                    ->whereBetween('expenses.date', [$range['start'], $range['end']])
                    ->whereNull('expenses.deleted_at');
            })
            ->select([
                'expense_categories.id as category_id',
                'expense_categories.name',
                'expense_categories.initial_balance',

                DB::raw('COALESCE(SUM(expenses.amount), 0) as total_amount'),
                DB::raw('COALESCE(SUM(expenses.point_amount), 0) as total_point'),
                DB::raw('COALESCE(SUM(expenses.amount) - SUM(expenses.point_amount), 0) as net_amount'),
                DB::raw('COUNT(expenses.id) as transaction_count'),
            ])
            ->where('expense_categories.is_active', true)
            ->groupBy([
                'expense_categories.id',
                'expense_categories.name',
                'expense_categories.initial_balance',
            ])
            ->orderBy('expense_categories.sort_order')
            ->get();
    }

    /*
    * 支払方法ごとの支出を取得
    */
    private function aggregateByPaymentMethod(array $range)
    {
        return ExpensePaymentMethod::query()
            ->leftJoin('expenses', function ($join) use ($range) {
                $join->on('expenses.payment_method_id', '=', 'expense_payment_methods.id')
                    ->whereBetween('expenses.date', [$range['start'], $range['end']])
                    ->whereNull('expenses.deleted_at');
            })
            ->select([
                'expense_payment_methods.id as payment_method_id',
                'expense_payment_methods.name',
                'expense_payment_methods.initial_balance',

                DB::raw('COALESCE(SUM(expenses.amount), 0) as total_amount'),
                DB::raw('COALESCE(SUM(expenses.point_amount), 0) as total_point'),
                DB::raw('COALESCE(SUM(expenses.amount) - SUM(expenses.point_amount), 0) as net_amount'),
                DB::raw('COUNT(expenses.id) as transaction_count'),
            ])
            ->where('expense_payment_methods.is_active', true)
            ->groupBy([
                'expense_payment_methods.id',
                'expense_payment_methods.name',
                'expense_payment_methods.initial_balance',
            ])
            ->orderBy('expense_payment_methods.sort_order')
            ->get();
    }

    /*
    * 日付ごとの支出を取得
    */
    private function aggregateByDate(array $range)
    {
        return DB::table('expenses')
            ->whereBetween('date', [$range['start'], $range['end']])
            ->whereNull('deleted_at')
            ->select([
                'date',

                DB::raw('SUM(amount) as total_amount'),
                DB::raw('SUM(point_amount) as total_point'),
                DB::raw('(SUM(amount) - SUM(point_amount)) as net_amount'),
                DB::raw('COUNT(*) as transaction_count'),
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
