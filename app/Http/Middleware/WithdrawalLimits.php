<?php

namespace App\Http\Middleware;

use App\BankAccount;
use App\Withdrawal;
use Closure;

class WithdrawalLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Max withdrawal per transaction = $20K
        if($request->amount > 20000) {
            abort(403, "Exceeded maximum withdrawal amount per transaction");
        }

        $withdrawals = Withdrawal::whereRaw('Date(created_at) = ?', date('Y-m-d'))->get();

        // Max withdrawal frequency = 3 transactions/day
        if ($withdrawals->count() == 3) {
            abort(403, "Exceeded maximum withdrawal transactions allowed per day");
        }

        $dailyTotal = 0;
        foreach ($withdrawals as $withdrawal) {
            $dailyTotal += $withdrawal->amount;
        }
        
        $dailyTotal += $request->amount;
        // Max withdrawal for the day = $50K    
        if ($dailyTotal > 50000) {
            abort(403, "Exceeded maximum withdrawal amount per day");
        }

        // Cannot withdraw when balance is less than withdrawal amount
        $account = BankAccount::find(1); // Only account we have
        if ($account->balance < $request->amount) {
            abort(403, "Cannot withdraw when balance is less than withdrawal amount");
        }

        return $next($request);
    }
}
