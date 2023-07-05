<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusProgramDegree extends Model
{
    use HasFactory;

    protected $table = 'campus_degree_programs';

    protected $fillable = ['campus_id', 'degree_id', 'program_id'];

    public function campus()
    {
        # code...
        return $this->belongsTo(Campus::class, 'campus_id');
    }

    public function degree()
    {
        # code...
        return $this->belongsTo(Degree::class, 'degree_id');
    }

    public function program()
    {
        # code...
        return $this->belongsTo(Program::class, 'program_id');
    }
}
