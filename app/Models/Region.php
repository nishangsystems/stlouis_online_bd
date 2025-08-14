<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';
    protected $connection = 'mysql2';

    public function divisions(){
        return $this->hasMany(Division::class, 'region_id');
    }
}
