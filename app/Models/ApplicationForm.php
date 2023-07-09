<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $fillable = [
        'student_id', 'year_id', 'gender', 'name', 'dob', 'pob', 'region', 'division', 'residence', 'phone', 'email',
        'program_first_choice', 'program_second_choice', 'first_spoken_language', 'first_written_language', 'second_spoken_language', 
        'second_written_language', 'has_health_problem', 'has_health_allergy', 'has_disability', 'health_problem', 'health_allergy', 'disability',
        'awaiting_results', 'previous_training', 'employments', 'fee_payer', 'fee_payer_name', 'fee_payer_residence',
        'fee_payer_tel', 'fee_payer_occupation', 'candidate_declaration', 'parent_declaration', 'campus_id', 'degree_id', 'transaction_id'
    ];

    public function student()
    {
        # code...
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function transaction()
    {
        # code...
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function year()
    {
        # code...
        return $this->belongsTo(Batch::class, 'year_id');
    }

    public function _region()
    {
        # code...
        return $this->belongsTo(Region::class, 'region');
    }

    public function _division()
    {
        # code...
        return $this->belongsTo(Division::class, 'division');
    }

}
