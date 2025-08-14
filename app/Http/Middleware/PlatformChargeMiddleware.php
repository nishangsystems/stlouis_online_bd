<?php

namespace App\Http\Middleware;

use Closure;

class PlatformChargeMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth('student')->user() == null) {
            # code...
            return redirect()->to(route('login'));
        }
        if(!(auth('student')->user()->hasPaidPlatformCharges())) {

            return redirect(route('student.platform_charge.pay'))->with('message', "Pay platform charges to continue");

        }

        return $next($request);

    }
}
