<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;
    protected $table = "charges";
    protected $connection = "mysql2";
    protected $fillable = ['year_id', 'semester_id', 'student_id', 'amount', 'item_id', 'transaction_id', 'parent', 'type', 'used', 'financialTransactionId'];
}
