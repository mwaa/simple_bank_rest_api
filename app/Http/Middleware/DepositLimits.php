<?php

namespace App\Http\Middleware;

use App\Deposit;
use Closure;
use Illuminate\Http\Request;

class DepositLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Max deposit per transaction = $40K
        if($request->amount > 40000) {
            abort(403, "Exceeded maximum deposit amount per transaction");
        }

        $deposits = Deposit::whereRaw('Date(created_at) = CURDATE()')->get();

        // Max deposit frequency = 4 transactions/day
        if ($deposits->count() == 4) {
            abort(403, "Exceeded maximum deposit transactions allowed per day");
        }

        $dailyTotal = 0;
        foreach ($deposits as $deposit) {
            $dailyTotal += $deposit->amount;
        }
        $dailyTotal += $request->amount;
        // Max deposit for the day = $150K    
        if ($dailyTotal > 150000) {
            abort(403, "Exceeded maximum deposit amount per day");
        }

        return $next($request);
    }
}
