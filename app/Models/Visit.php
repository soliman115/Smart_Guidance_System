<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{

    protected $fillable = ['user_id', 'place_id', 'visited_at'];

    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Place()
    {
        return $this->belongsTo(Place::class);
    }
    public $timestamps = true;
}
