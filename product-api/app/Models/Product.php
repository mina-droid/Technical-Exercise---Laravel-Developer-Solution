<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function options()
    {
        return $this->belongsToMany(Option::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function defaultVariant()
    {
        return $this->belongsTo(Variant::class, 'default_variant_id');
    }

    public function getVariantsWithOptionsAttribute()
    {
        return $this->variants()->with('options')->get();
    }
}
