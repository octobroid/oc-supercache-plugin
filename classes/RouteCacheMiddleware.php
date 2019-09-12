<?php namespace Octobro\SuperCache\Classes;

use Cache;
use Config;
use Schema;
use Closure;
use Response;
use Redirect;
use Illuminate\Http\Request;
use Octobro\SuperCache\Models\CacheRoute;

class RouteCacheMiddleware
{
    /**
     * Handle request
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        // Check cached routes
        if (!Schema::hasTable('octobro_supercache_routes') || !($cacheRow = $this->shouldBeCached($request))) {
            return $next($request);
        }

        $cacheKey = $this->getCacheKey($request->fullUrl());

        // Response if has cache and not blank
        if (Cache::has($cacheKey)) {
            return $this->responseCachedContent($cacheKey);
        }

        $response = $next($request);

        // Save response if not blank
        if ($response->getContent()) {
            Cache::put($cacheKey, [
                'headers' => [
                    'Content-Type' => $response->headers->get('Content-Type'),
                ],
                'content' => $response->getContent(),
            ], array_get($cacheRow, 'cache_ttl'));
        }

        $response->header('X-Octobro-SuperCache', 'MISS');

        return $response;
    }

    protected function responseCachedContent($cacheKey)
    {
        $cachedResponse = Cache::get($cacheKey);

        $response = Response::make(array_get($cachedResponse, 'content'), 200);

        foreach (array_get($cachedResponse, 'headers') as $key => $value) {
            $response->header($key, $value);
        }
        
        $response->header('X-Octobro-SuperCache', 'HIT');

        return $response;
    }

    /**
     * Generate cache key
     *
     * @param string $url
     * @return string
     */
    protected function getCacheKey($url)
    {
        return 'octobro.supercache.url.' . $url;
    }

    /**
     * Check is request should be cached
     *
     * @param Request $request
     * @return bool
     */
    protected function shouldBeCached(Request $request)
    {
        // Only GET request
        if ($request->method() != 'GET') return false;

        // Disable backend
        if ($request->is(Config::get('cms.backendUri') . '*')) return false;

        // Getting cache patterns
        $cacheRouteRows = Cache::remember('octobro.supercache.cachedRoutes',
            Config::get('cms.urlCacheTtl'),
            function () {
                return CacheRoute::orderBy('sort_order')->get()->toArray();
            }
        );

        // Check matched route pattern
        foreach ($cacheRouteRows as $cacheRow) {
            if ($request->is(array_get($cacheRow, 'route_pattern'))) {
                return $cacheRow;
            }
        }

        return false;
    }
}
