<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CronArea
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) {
            return $next($request);
        }

        return redirect(route('admin.login'));
    }
}
