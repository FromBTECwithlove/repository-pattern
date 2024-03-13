<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    protected array $filterable = [];

    public function filterName($query, $value) {
        $value = "%{$value}%";
        return $query->where('name', 'LIKE', $value)
            ->orWhere('slug', 'LIKE', $value);
    }

    public $timestamps = true;
}
