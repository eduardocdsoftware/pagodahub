<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'id_category',
        'id_brand'
    ];  

    public function brand(): HasOne
    {
        return $this->hasOne(Brand::class, 'id', 'id_brand');
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'id_category');
    }

}