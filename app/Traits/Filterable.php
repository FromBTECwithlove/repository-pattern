<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Filterable
{
    public function scopeFilter($query, $params)
    {
        foreach ($params as $field => $value) {
            if (!isset($value)) continue;

            $method = 'filter' . Str::studly($field);

            if (method_exists($this, $method)) {
                $this->{$method}($query, $value);
            }

            if (!empty($this->filterable) && is_array($this->filterable)) {
                if (in_array($field, $this->filterable)) {
                    $query->where($field, $value);
                }

                if (key_exists($field, $this->filterable)) {
                    $query->where($this->filterable[$field], $value);
                }
            }
        }

        return $query;
    }
}
