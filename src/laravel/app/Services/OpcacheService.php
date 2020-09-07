<?php

namespace App\Services;
use Symfony\Component\Finder\Finder;

class OpcacheService
{

    /**
     * Clear OPcache.
     */
    public function clear()
    {
        if (function_exists('opcache_reset')) {
            return opcache_reset();
        }
    }

    /**
     * Get configuration values.
     */
    public function getConfig()
    {
        if (function_exists('opcache_get_configuration')) {
            return opcache_get_configuration();
        }
    }

    /**
     * Get status info.
     */
    public function getStatus()
    {
        if (function_exists('opcache_get_status')) {
            return opcache_get_status(false);
        }
    }

    /**
     * Pre-compile php scripts.
     *
     * @param bool $force
     * @return array
     */
    public function compile($force = false)
    {
        if (! ini_get('opcache.dups_fix') && ! $force) {
            return ['message' => 'opcache.dups_fix must be enabled, or run with --force'];
        }

        if (function_exists('opcache_compile_file')) {
            $compiled = 0;

            // Get files in these paths
            $files = collect(Finder::create()->in(config('opcache.directories'))
                                   ->name('*.php')
                                   ->ignoreUnreadableDirs()
                                   ->notContains('#!/usr/bin/env php')
                                   ->exclude(config('opcache.exclude'))
                                   ->files()
                                   ->followLinks());

            // optimized files
            $files->each(function ($file) use (&$compiled) {
                try {
                    if (! opcache_is_script_cached($file)) {
                        opcache_compile_file($file);
                    }

                    $compiled++;
                } catch (\Exception $e) {
                }
            });

            return [
                'total_files_count' => $files->count(),
                'compiled_count'    => $compiled,
            ];
        }
    }
    /**  */

    public function size_for_humans($bytes)
    {
        if ($bytes > 1048576) {
            return sprintf("%.2f&nbsp;MB", $bytes/1048576);
        } elseif ($bytes > 1024) {
            return sprintf("%.2f&nbsp;kB", $bytes/1024);
        } else {
            return sprintf("%d&nbsp;bytes", $bytes);
        }
    }
}
