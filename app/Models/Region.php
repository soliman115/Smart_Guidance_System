<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Region extends Model
{
    use HasFactory;
    protected $fillable=['id','region_name','x_coordinate','y_coordinate'];
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;
    


    public function places()
    {
        return $this->hasMany(Place::class);
    } 
}
