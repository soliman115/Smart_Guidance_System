<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model

{

    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'service_id', 'department', 'photo'];
    ////belong
    // public function Place(){
    //     return $this->belongsTo(Place::class);
    // }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    // public function building(){
    //     return $this->belongsTo(Building::class);
    // }
}