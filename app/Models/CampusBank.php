<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusBank extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $fillable = ['campus_id', 'bank_name', 'account_name', 'account_number'];
}
