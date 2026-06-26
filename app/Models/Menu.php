<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'category_id',
        'menu_name',
        'menu_slug',
        'menu_description', 
        'menu_price',
        'menu_image',
        'is_available'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
