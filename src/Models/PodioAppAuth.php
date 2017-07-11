<?php

namespace PodioAuth\Models;

use Illuminate\Database\Eloquent\Model;

class PodioAppAuth extends Model
{
    protected $fillable = [
        'app_id', 'app_secret'
    ];
}
