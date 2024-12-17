<?php

namespace HuserG\LaravelExpirationMailer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expiration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'expiration_date',
        'emails',
        'message',
    ];

    protected $casts = [
        'emails' => 'array', // Stocker les emails sous forme JSON
        'expiration_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
