<?php

namespace App\Models;

use App\Helpers\DurationOfReading;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title' , 'user_id' , 'description' , 'image'];

    public function user()
    {
      return  $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class , 'commentable');
    }

    public function readingDuration(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>
             (new DurationOfReading())->setText($this->description)->getDurationPerMinutes()
        );
    }
}
