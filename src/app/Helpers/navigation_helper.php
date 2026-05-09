<?php

if (!function_exists('navActive')) {
    /**
     * Returns "active" when current URL path matches one of given menu paths.
     */
    function navActive($paths, ?string $currentUrl = null): string
    {
        $currentUrl = $currentUrl ?? current_url();
        $currentPath = trim((string) parse_url($currentUrl, PHP_URL_PATH), '/');

        foreach ((array) $paths as $path) {
            $path = trim((string) $path, '/');

            if ($path === '' && $currentPath === '') {
                return 'active';
            }

            if ($path !== '' && ($currentPath === $path || str_starts_with($currentPath, $path . '/'))) {
                return 'active';
            }
        }

        return '';
    }
}
