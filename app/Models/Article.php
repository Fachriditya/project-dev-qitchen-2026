<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'article_id';

    protected $fillable = [
        'admin_id', 
        'article_title', 
        'article_slug', 
        'article_content', 
        'thumbnail', 
        'is_published', 
        'published_at'
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }
}