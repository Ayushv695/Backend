<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $table = 'schools';

    protected $fillable = [
        'schoolName',
        'email',
        'status',
        'schoolCode',
        'affiliatedTo',
        'establishedYear',
        'address',
        'state',
        'country',
        'city',
        'phoneNo',
    ];
}
