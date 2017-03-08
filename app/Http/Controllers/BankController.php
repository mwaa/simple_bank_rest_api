<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Deposit;
use App\Withdrawal;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Get balance for first account in database
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance()
    {
        $account = BankAccount::find(1);
        return response()->json($account->toArray());
    }

    /**
     * Make a new deposit to account
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deposit(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
        ]);
        $deposit = new Deposit();
        $deposit->fill(array_only($request->all(), $deposit->getFillable()));
        $deposit->save();
        return response()->json($deposit->toArray(), 201);
    }

    /**
     * Make a new withdrawal to account
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
        ]);
        $withdraw = new Withdrawal();
        $withdraw->fill(array_only($request->all(), $withdraw->getFillable()));
        $withdraw->save();
        return response()->json($withdraw->toArray(), 201);
    }
}
