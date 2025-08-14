<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingTranzakTransaction extends Model
{
    use HasFactory;
    protected $connection = "mysql2";
    protected $fillable = [
        'form_id', 'requestId', 'payment_id', 'student_id', 'year_id', 'campus_id', 'purpose', 'transaction'
    ];
}
