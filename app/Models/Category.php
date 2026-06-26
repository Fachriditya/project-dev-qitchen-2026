<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
        'category_slug'
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'category_id', 'category_id');
    }
}
