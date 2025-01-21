<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'pages_id';
    
    protected $fillable = [
        'title',
        'thumbnail',
        'menus_id',
        'content',
        'content_image',
        'user_id'
    ];

    // Relasi ke tabel menus
    public function menu()
    {
        return $this->hasOne(Menu::class, 'page_id', 'pages_id');
    }

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}