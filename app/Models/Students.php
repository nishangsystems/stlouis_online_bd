<?php

namespace App\Models;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Students extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'gender',
        'username',
        'matric',
        'dob',
        'pob',
        'campus_id',
        'admission_batch_id',
        'password',
        'parent_name',
        'program_id',
        'parent_phone_number',
        'imported',
        'active'
    ];
    protected $connection = 'mysql2';

    public function extraFee($year_id)
    {
        $builder = $this->hasMany(ExtraFee::class, 'student_id')->where('year_id', '=', $year_id);
        return $builder->count() == 0 ? null : $builder->first();
    }
    
    public function _class($year=null)
    {
        return $this->belongsToMany(ProgramLevel::class, 'student_classes', 'student_id', 'class_id')->where(function($q)use($year){
            $year == null  ?
                $q->where('year_id', '<=', Helpers::instance()->getCurrentAccademicYear()) :
                $q->where('year_id', '=', $year);
        })->orderByDesc('year_id')->first();
    }

    public function class($year)
    {
        // return CampusProgram::where('campus_id', $this->campus_id)->where('program_level_id', $this->_class($year)->id)->first();
        return $this->_class($year)->campus_programs($this->campus_id)->first() ?? null;
    }

    public function classes()
    {
        return $this->hasMany(StudentClass::class, 'student_id');
    }

    public function result()
    {
        return $this->hasMany(Result::class, 'student_id');
    }

    public function offline_result()
    {
        return $this->hasMany(OfflineResult::class, 'student_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'student_id');
    }

    public function payIncomes($year)
    {
        # code...
        return $this->hasMany(PayIncome::class, 'student_id')->where('batch_id', '=', $year);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function total($year_id = null)
    {
        $year = $year_id == null ? Helpers::instance()->getCurrentAccademicYear() : $year_id;
        if ($this->classes()->where('year_id', $year)->first() != null) {
            # code...
            // return $this->campus()->first()->campus_programs()->where(['program_level_id' => $this->_class(Helpers::instance()->getCurrentAccademicYear())->id ?? 0, 'campus_id'=>$this->campus_id])->first()->payment_items()->first()->amount ?? -1;
            $rec = $this->_class($year)->campus_programs($this->campus_id)->first()->payment_items()->where(['year_id'=>$year, 'name'=>'TUTION'])->first();
            return $rec ? $rec->amount : 0;
        }
        
        return 0;
    }

    public function paid()
    {
        $items = $this->payments()->selectRaw('COALESCE(sum(amount),0) total')->where('batch_id', Helpers::instance()->getYear())->get();
        return $items->first()->total;
    }

    public function bal($student_id = null, $year = null)
    {
        $year = $year == null ? Helpers::instance()->getCurrentAccademicYear() : $year;
        $scholarship = Helpers::instance()->getStudentScholarshipAmount($this->id);
        return $this->total() + $this->debt($year) + ($this->extraFee($year) == null ? 0 : $this->extraFee($year)->amount) - $this->paid() - ($scholarship);
    }


    public function totalScore($sequence, $year)
    {
        $class = $this->class($year);
        $subjects = $class->subjects;
        $total = 0;
        foreach ($subjects as $subject) {
            $total += Helpers::instance()->getScore($sequence, $subject->id, $class->id, $year, $this->id) * $subject->coef;
        }

        return $total;
    }

    public function averageScore($sequence, $year)
    {
        $total = $this->totalScore($sequence, $year);
        $gtotal = 0;
        $class = $this->class($year);
        $subjects = $class->subjects;
        foreach ($subjects as $subject) {
            $gtotal += 20 * $subject->coef;
        }
        if ($gtotal == 0 || $total == 0) {
            return 0;
        } else {
            return number_format((float)($total / $gtotal) * 20, 2);
        }
    }

    
    



    // FOR APPLICATION PORTAL ONLY
    public function currentApplicationForms($year = null)
    {
        $year = $year == null ? Helpers::instance()->getCurrentAccademicYear() : $year;
        return $this->hasMany(ApplicationForm::class, 'student_id')->where('application_forms.year_id', $year);
    }

    public function applicationForms()
    {
        return $this->hasMany(ApplicationForm::class, 'student_id');
    }

}
