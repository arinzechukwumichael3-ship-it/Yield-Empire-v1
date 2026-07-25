<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Prevent Cloudflare and browsers from caching authenticated /user* pages.
 *
 * The site sits behind Cloudflare; without this, edited authenticated pages
 * can be served from the edge cache even after a fresh deploy.
 */
class PreventAuthPageCaching
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (str_starts_with(ltrim($request->path(), '/'), 'user')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
