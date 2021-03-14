<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

abstract class ModelResource extends JsonResource
{
    public Request $request;

    public bool $identifier = true;

    public bool $fillable = true;

    public bool $timestamps = true;

    public function toArray($request): array
    {
        $this->request = $request;

        if ($this->request->has('withAppends')) {
            $this->setAppends($this->request->get('withAppends'));
        }

        if ($request->has('withCount')) {
            $this->withCount($request->get('withCount'));
        }

        return array_merge([
            $this->mergeWhen($this->identifier, [
                $this->getKeyName() => $this->whenAttribute($this->getKeyName()),
            ]),
            $this->mergeWhen($this->fillable, $this->getFillableAttributes()),
        ], $this->transformTo());
    }

    public static function items($resource)
    {
        $resource->pagination = [
            'total' => $resource->total(),
            'count' => $resource->count(),
            'per_page' => (int) $resource->perPage(),
            'current_page' => $resource->currentPage(),
            'total_pages' => $resource->lastPage(),
        ];
        return parent::collection($resource);
    }

    abstract public function transformTo(): array;

    protected function getFillableAttributes(): array
    {
        $attributes = [];

        foreach ($this->getFillable() as $attribute) {
            if ($this->timestamps && in_array($attribute, $this->getDates())) {
                $attributes[$attribute] = $this->whenAttribute($attribute, optional($this->getAttribute($attribute))->toDateTimeString());
            } else {
                $attributes[$attribute] = $this->whenAttribute($attribute);
            }
        }

        if ($this->timestamps) {
            $createdAtColumn = $this->getCreatedAtColumn();
            $updatedAtColumn = $this->getUpdatedAtColumn();
            $attributes[$createdAtColumn] = $this->whenAttribute($createdAtColumn, optional($this->getAttribute($createdAtColumn))->toDateTimeString());
            $attributes[$updatedAtColumn] = $this->whenAttribute($updatedAtColumn, optional($this->getAttribute($updatedAtColumn))->toDateTimeString());

            if (in_array(SoftDeletes::class, class_uses($this->resource))) {
                $attributes['deleted_at'] = $this->whenAttribute('deleted_at', optional($this->deleted_at)->toDateTimeString());
            }
        }

        return $attributes;
    }

    /**
     * Retrieve an accessor when it has been appended attribute.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  mixed  $default
     * @return \Illuminate\Http\Resources\MissingValue|mixed
     */
    protected function whenAttribute($attribute, $value = null, $default = null)
    {
        if (array_has($this->getAttributes(), $attribute)) {
            return func_num_args() >= 2 ? value($value) : $this->{$attribute};
        }

        return func_num_args() === 3 ? value($default) : new MissingValue();
    }
}
