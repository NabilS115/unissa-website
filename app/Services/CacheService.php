<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    // Cache durations in minutes
    const PRODUCT_CACHE_DURATION = 60; // 1 hour
    const FEATURED_CACHE_DURATION = 30; // 30 minutes
    const SEARCH_CACHE_DURATION = 15; // 15 minutes
    const STATS_CACHE_DURATION = 120; // 2 hours

    /**
     * Get cache key with prefix
     */
    public static function getKey(string $key): string
    {
        return config('cache.prefix') . $key;
    }

    /**
     * Cache product data with proper expiration
     */
    public static function cacheProducts(string $type, $data, int $duration = null): bool
    {
        try {
            $duration = $duration ?? self::PRODUCT_CACHE_DURATION;
            return Cache::put("products.browse.{$type}", $data, now()->addMinutes($duration));
        } catch (\Exception $e) {
            Log::error('Failed to cache products', ['type' => $type, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Cache featured products with shorter duration
     */
    public static function cacheFeatured(string $type, $data): bool
    {
        try {
            return Cache::put("products.featured.{$type}", $data, now()->addMinutes(self::FEATURED_CACHE_DURATION));
        } catch (\Exception $e) {
            Log::error('Failed to cache featured products', ['type' => $type, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Smart cache clearing with fallback
     */
    public static function clearProductCaches(?string $productType = null): bool
    {
        try {
            $cacheKeys = [
                'products.browse.food',
                'products.browse.merch', 
                'products.browse.others',
                'products.featured.food',
                'products.featured.merch',
                'products.featured.others',
                'products.categories',
                'admin.product.stats'
            ];

            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }

            // Clear search related caches
            $searchTypes = ['food', 'merch', 'others'];
            foreach ($searchTypes as $type) {
                Cache::forget("search.{$type}");
            }

            Log::info('Product caches cleared successfully', ['type' => $productType]);
            return true;

        } catch (\Exception $e) {
            Log::error('Cache clearing failed', ['error' => $e->getMessage(), 'type' => $productType]);
            return false;
        }
    }

    /**
     * Check cache health
     */
    public static function healthCheck(): array
    {
        try {
            // Test cache write/read
            $testKey = 'cache.health.test';
            $testValue = time();
            
            Cache::put($testKey, $testValue, 60);
            $retrieved = Cache::get($testKey);
            Cache::forget($testKey);
            
            return [
                'status' => $retrieved === $testValue ? 'healthy' : 'unhealthy',
                'driver' => config('cache.default'),
                'writable' => $retrieved === $testValue,
                'timestamp' => now()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'driver' => config('cache.default'),
                'timestamp' => now()
            ];
        }
    }
}