<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Str;

class FilterService
{
    public const modelNameSpace = '\App\Models\\';

    public static function instance(): self
    {
        return new static();
    }

    public function filterKeysByModel(string $model)
    {
        try {
            $modelClass = self::modelNameSpace . Str::ucfirst($model);
            throw_unless(class_exists($modelClass), \RuntimeException::class, 'class not found');

            return array_keys($modelClass::rules());
        } catch (\Throwable $e) {
            throw new \Exception($e);
        }
    }
}
