<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Sortable
{
    public function scopeSort($query, $orders)
    {
        foreach ($orders as $field => $value) {
            $method = 'sort' . Str::studly($field);

            if (method_exists($this, $method)) {
                $this->{$method}($query, $value);
            }

            if (!empty($this->sortable) && is_array($this->sortable))
            {
                if (in_array($field, $this->sortable)) {
                    $query->orderBy($field, $value);
                }

                if (key_exists($field, $this->sortable)) {
                    $query->orderBy($this->sortable[$field], $value);
                }
            }
        }

        return $query;
    }
}
