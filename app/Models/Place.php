<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    protected $fillable=['id','name','region','guide_word','x_coordinate','y_coordinate','building_id'];
    
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

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
