<?php

if (!function_exists('localeRoute')) {
    function localeRoute(string $locale, string $named, array $params = []): string
    {
        return route($named, array_merge(['locale' => $locale], $params));
    }
}

if (!function_exists('currentLocale')) {
    function currentLocale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('localizedUrl')) {
    /**
     * Prefix an internal URL path with the current locale.
     * External URLs (starting with http) and anchors (#) are returned unchanged.
     */
    function localizedUrl(string $path): string
    {
        if (str_starts_with($path, 'http') || str_starts_with($path, '#')) {
            return $path;
        }
        $locale = app()->getLocale();
        return '/' . $locale . '/' . ltrim($path, '/');
    }
}
