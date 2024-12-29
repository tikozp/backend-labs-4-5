<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}