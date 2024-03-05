<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Variant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'option1',
        'option2',
        'price',
        'stock',
        'is_in_stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class);
    }
}
