<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusDegree extends Model
{
    use HasFactory;
    protected $fillable = ['campus_id', 'degree_id'];

    public function campus()
    {
        # code...
        return $this->belongsTo(Campus::class);
    }

    public function degree()
    {
        # code...
        return $this->belongsTo(Degree::class);
    }

}
