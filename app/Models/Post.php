<?php

namespace App\Models;

use Orbit\Concerns\Orbital;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use Orbital;

    protected $fillable = [
        'title',
        'url',
        'category',
        'author',
        'content',
        'published_at',
    ];

    public static function schema(Blueprint $table)
    {
        $table->id();
        $table->string('title')->nullable();
        $table->string('url')->nullable();
        $table->string('category')->nullable();
        $table->string('author')->nullable();
        $table->string('content')->nullable();
        $table->timestamp('published_at')->nullable();
    }
}
