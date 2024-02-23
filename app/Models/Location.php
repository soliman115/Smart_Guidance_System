<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'building_id'];
    /////has
    // public function employee(){
    //     return $this->hasMany(Employee::class);
    // }
    public function Service()
    {
        return $this->hasMany(Service::class);
    }


    ///belong
    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
