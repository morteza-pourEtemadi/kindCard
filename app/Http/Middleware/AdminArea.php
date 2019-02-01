<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminArea
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->cookie('user')) {
            $token = base64_decode(base64_decode($request->cookie('user')));
            if ($token == '101538817') {
                return $next($request);
            }
        }

        return redirect(route('admin.login'));
    }
}
