<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterTransactionsRequest;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(public readonly TransactionService $transactionService)
    {

    }

    public function index(FilterTransactionsRequest $request)
    {
        $user = $request->user();
        $transactionBuilder = $this->transactionService->modelQuery()
                            ->when($request->query('category'), function($query, $category) {
                            $query->where('category', $category);
                            })->when($request->query('start_date'), function($query, $start_date) use($request){
                                $end_date = $request->query('end_date');
                                // if(($start_date && $end_date) == false){
                                //     return $query;
                                // }
                                $query->whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date);
                                });
        $transactionBuilder = $this->transactionService->getTransactionByUserId($user->id, $transactionBuilder);

        return $this->sendSuccess(['transaction' => $transactionBuilder->paginate($request->query('per_page', 15))]);
    }
}
