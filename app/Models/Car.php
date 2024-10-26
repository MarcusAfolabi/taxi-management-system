<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        "car_name",
        "model",
        "price",
        "availability_status"
    ];
    protected $table = "cars";
}
