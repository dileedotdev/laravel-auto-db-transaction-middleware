<?php

namespace Dinhdjj\AutoDBTransaction;

use Closure;
use Dinhdjj\AutoDBTransaction\Facades\AutoDBTransaction;
use Illuminate\Http\Request;

class AutoDBTransactionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Request|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ('GET' === $request->getMethod()) {
            return $next($request);
        }

        AutoDBTransaction::beginTransaction();

        $response = $next($request);

        AutoDBTransaction::commit();

        return $response;
    }
}
