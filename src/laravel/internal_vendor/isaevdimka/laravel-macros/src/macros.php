<?php

declare(strict_types=1);

\Illuminate\Support\Collection::macro(
    'mapKeysWith',
    function ($callable) {
        /** @var \Illuminate\Support\Collection $this */
        return $this->mapWithKeys(function ($item, $key) use ($callable) {
            if (is_array($item)) {
                $item = collect($item)
                    ->mapKeysWith($callable)
                    ->toArray();
            }
            return [
                $callable($key) => $item,
            ];
        });
    }
);

\Illuminate\Support\Collection::macro(
    'mapKeysToCamelCase',
    function () {
        /** @var \Illuminate\Support\Collection $this */
        return $this->mapKeysWith('camel_case');
    }
);


/**
 * Example using: $query->whereSpatialDistance('coordinates', [1, 1], 10);
 */
\Illuminate\Database\Query\Builder::macro(
    'whereSpatialDistance',
    function ($column, $operator, $point, $distance, $boolean = 'and') {
        $this->whereRaw(
            "ST_Distance_Sphere(`{$this->from}`.`${column}`, POINT(?, ?)) ${operator} ?",
            [$point[0], $point[1], $distance],
            $boolean
        );
    }
);
/**
 * Example using: $query->orWhereSpatialDistance('coordinates', [0, 0], 1);
 */
\Illuminate\Database\Query\Builder::macro(
    'orWhereSpatialDistance',
    function ($column, $operator, $point, $distance) {
        $this->whereSpatialDistance($column, $operator, $point, $distance, 'or');
    }
);

/**
 * Example using: File::extractZip($path, $extractTo);
 */
\Illuminate\Filesystem\Filesystem::macro(
    'extractZip',
    function ($path, $extractTo) {
        $zip = new ZipArchive();
        $zip->open($path);
        $zip->extractTo($extractTo);
        $zip->close();
    }
);

\Illuminate\Validation\Rule::mixin(new \App\RulesMixin());
