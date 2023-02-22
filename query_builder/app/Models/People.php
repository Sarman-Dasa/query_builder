<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;
    protected $fillable = [
        'people_name',
            'people_email',
            'people_number',
            'people_address',
            'people_birthdate',
            'people_gender',
            'people_gender'
    ];
  
}
