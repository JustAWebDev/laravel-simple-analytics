<?php
namespace JustAWebDev\Analytics\Middleware;

use Closure;
use Illuminate\Support\Str;

class TrackPageView
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        if (!config('analytics.enabled')) {
            return $response;
        }

        $userAgent = $request->userAgent();

        if (Str::contains($userAgent, ['bot', 'crawl', 'spider'])) {
            return $response;
        }

        app('analytics')->pageView([
            'name' => $request->path(),
            'method' => $request->method(),
            'status' => $response->getStatusCode(),
            'duration_ms' => (microtime(true) - $start) * 1000,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
        ]);

        return $response;
    }
}