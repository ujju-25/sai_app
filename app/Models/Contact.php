<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Add 'name' to the fillable property
        'email',
        'phone',
        // Add other fields as needed
    ];
}
