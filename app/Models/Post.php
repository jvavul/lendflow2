<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $hidden = [
        'updated_at',
        'created_at',
        'file_id',
        'user_id',
    ];

    public function tags()
    {
        return $this->hasManyThrough(Tag::class, TagPost::class, 'post_id', 'id', 'id', 'tag_id');
    }

    public function main_image()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }
    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
