<?php

declare(strict_types=1);

namespace App\Models\Concerns;

trait UsesActive
{
    /**
     * Scope a query to only include active items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', '=', true);
    }

    /**
     * Scope a query to only include not active items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('is_active', '=', false);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isNotActive(): bool
    {
        return ! $this->isActive();
    }
}
