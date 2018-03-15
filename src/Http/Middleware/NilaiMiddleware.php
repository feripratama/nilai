<?php

namespace Bantenprov\Nilai\Http\Middleware;

use Closure;

/**
 * The NilaiMiddleware class.
 *
 * @package Bantenprov\Nilai
 * @author  bantenprov <developer.bantenprov@gmail.com>
 */
class NilaiMiddleware
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
        return $next($request);
    }
}
