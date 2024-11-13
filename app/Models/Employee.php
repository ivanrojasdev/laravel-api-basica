<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $fillable = ['dni', 'first_name', 'last_name', 'gender', 'email'];
    protected $hidden = ['created_at', 'updated_at'];
}
