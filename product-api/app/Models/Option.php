<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Option extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'values',
    ];

    protected $casts = [
        'values' => 'array',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function variants()
    {
        return $this->belongsToMany(Variant::class);
    }
}
