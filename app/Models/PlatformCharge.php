<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformCharge extends Model
{
    use HasFactory;

    protected $table = "platform_charges";
    protected $connection = "mysql2";
    protected $fillable = ['student_id', 'year_id', 'amount', 'item_id', 'semester_id', 'transaction_id', 'parent', 'type', 'financialTransactionId', 'used'];
}
