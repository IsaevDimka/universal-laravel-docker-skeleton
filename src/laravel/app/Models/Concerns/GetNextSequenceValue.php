<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Support\Facades\DB;

trait GetNextSequenceValue
{
    public function getNextSequenceValue(): int
    {
        $self = new static();

        if (! $self->getIncrementing()) {
            throw new \Exception(sprintf('Model (%s) is not auto-incremented', static::class));
        }

        $sequenceName = "{$self->getTable()}_id_seq";

        return DB::selectOne("SELECT nextval('{$sequenceName}') AS val")->val;
    }
}
