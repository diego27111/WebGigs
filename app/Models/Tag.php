<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'id';
    public $incrementing = true;

    public function listings() {
        return $this->belongsToMany(Listing::class);
    }
}
