<?php

namespace Modules\Product\Services;

use Illuminate\Support\Str;

class SlugGenerator
{
    public static function unique(string $value, callable $exists): string
    {
        $base = Str::slug($value);
        $count = 1;
        while ($exists($base.'-'.$count)) {
            $count++;
        }

        return $base.'-'.mb_str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
