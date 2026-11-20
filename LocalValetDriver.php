<?php

use Valet\Drivers\LaravelValetDriver;

/**
 * Serve Laravel from the project root index.php (outside /public),
 * while keeping static assets under /public.
 */
class LocalValetDriver extends LaravelValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return file_exists($sitePath.'/artisan')
            && file_exists($sitePath.'/index.php');
    }

    public function isStaticFile(string $sitePath, string $siteName, string $uri)
    {
        if ($uri === '/') {
            return false;
        }

        $publicFile = $sitePath.'/public'.$uri;

        if ($this->isActualFile($publicFile)) {
            return $publicFile;
        }

        return false;
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): string
    {
        $_SERVER['SCRIPT_FILENAME'] = $sitePath.'/index.php';
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        return $sitePath.'/index.php';
    }
}
