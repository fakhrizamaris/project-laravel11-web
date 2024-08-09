<?php

namespace Illuminate\Foundation\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\PostTooLargeException;

class ValidatePostSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Http\Exceptions\PostTooLargeException
     */
    public function handle($request, Closure $next)
    {
        $max = $this->getPostMaxSize();

        if ($request->server('CONTENT_LENGTH') > $max) {
            throw new PostTooLargeException;
        }

        return $next($request);
    }

    /**
     * Determine the server 'post_max_size' as bytes.
     *
     * @return int
     */
    protected function getPostMaxSize()
    {
        return $this->convertToBytes(ini_get('post_max_size'));
    }

    /**
     * Convert a given size with a PHP ini style size notation to bytes.
     *
     * @param  string  $size
     * @return int
     */
    protected function convertToBytes($size)
    {
        $units = ['B', 'K', 'M', 'G', 'T', 'P', 'E'];
        $size = strtoupper($size);
        $unit = preg_replace('/[^BKMGTPE]/', '', $size);
        $size = floatval($size);

        return $size * pow(1024, array_search($unit, $units));
    }
}
