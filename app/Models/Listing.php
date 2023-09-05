<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Attachment\Models\Attachment;

class Listing extends Model
{
    use HasFactory, AsSource, Attachable, Filterable;

    protected $guarded = [];

    protected $allowedSorts = [
        'title',
        'created_at',
        'updated_at'
    ];

    protected $allowedFilters = [
        'title' => Like::class,
        'created_at' => Like::class,
    ];


    public function getRouteKeyName() {
        return 'slug';
    }

    public function clicks() {
        return $this->hasMany(Click::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
}
