<?php

namespace JustAWebDev\Analytics;

use JustAWebDev\Analytics\Models\AnalyticsEvent;
use Illuminate\Support\Str;

class Analytics
{
    public function track(string $name, array $meta = [])
    {
        AnalyticsEvent::create([
            'type' => 'event',
            'name' => $name,
            'meta' => $meta,
        ]);
    }

    public function pageView(array $data)
    {
        $ignoreRoutes = config('analytics.ignore_routes', []);
        $routeName = $data['name'] ?? null;

        if ($routeName && $this->shouldIgnoreRoute($routeName, $ignoreRoutes)) {
            return;
        }

        AnalyticsEvent::create(array_merge([
            'type' => 'pageview',
        ], $data));
    }

    public function count(string $type, ?string $name = null)
    {
        $query = AnalyticsEvent::where('type', $type);

        if ($name) {
            $query->where('name', $name);
        }

        return $query->count();
    }

    public function topPages(int $limit = 5)
    {
        return AnalyticsEvent::where('type', 'pageview')
            ->selectRaw('name, count(*) as total')
            ->groupBy('name')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    private function shouldIgnoreRoute(string $routeName, array $ignoreRoutes): bool
    {
        foreach ($ignoreRoutes as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return true;
            }
        }
        return false;
    }
}