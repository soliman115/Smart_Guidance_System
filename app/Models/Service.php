<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'place_id', 'description'];

    /////has
    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }
    ////belong
    public function Place()
    {
        return $this->belongsTo(Place::class);
    }
}
