<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'address', 'description', 'longitude', 'latitude', 'photo'];
    ////has
    public function place()
    {
        return $this->hasMany(Place::class);
    }

    // public function service(){
    //     return $this->hasMany(Service::class);
    // }

    // public function employee(){
    //     return $this->hasMany(Employee::class);
    // }

}
