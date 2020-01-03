<?php

namespace App\Http\Middleware;

use App\Shift;
use Closure;

class CheckOrders
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
        if(Shift::getCurrentShift()){
            return $next($request);
        }
        return abort(403,'Not allowed to make any order when there is no shift opened');
    }
}
