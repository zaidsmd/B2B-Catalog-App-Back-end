<?php

use Illuminate\Support\Str;

if (! function_exists('slugify')) {
    /**
     * Convert the given string into a URL-friendly slug.
     *
     * @param  string  $value  The input string to slugify.
     * @param  string  $separator  The word separator to use in the slug (default: '-')
     * @return string The slugified string.
     */
    function slugify(string $value, string $separator = '-'): string
    {
        return Str::slug($value, $separator);
    }
}
