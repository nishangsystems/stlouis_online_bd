<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id', 'year_id', 'gender', 'name', 'dob', 'pob', 'region', 'division', 'residence', 'phone', 'email',
        'program_first_choice', 'program_second_choice', 'first_spoken_language', 'first_written_language', 'second_spoken_language', 
        'second_written_language', 'has_health_problem', 'has_health_allergy', 'has_disability', 'health_problem', 'health_allergy', 'disability',
        'awaiting_results', 'previous_training', 'employments', 'fee_payer', 'fee_payer_name', 'fee_payer_residence',
        'fee_payer_tel', 'fee_payer_occupation', 'candidate_declaration', 'parent_declaration', 'submitted', 'campus_id', 'degree_id'
    ];

    public function student()
    {
        # code...
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function programFirstChoice()
    {
        # code...
        return $this->belongsTo(SchoolUnits::class, 'program_first_choice');
    }

    public function programSecondChoice()
    {
        # code...
        return $this->belongsTo(SchoolUnits::class, 'program_second_choice');
    }

    public function year()
    {
        # code...
        return $this->belongsTo(Batch::class, 'year_id');
    }

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

}
