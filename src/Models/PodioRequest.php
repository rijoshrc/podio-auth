<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PodioRequest extends Model
{
    protected $fillable = [
        'id',
        'request',
        'app_id',
        'is_processed',
        'is_processing'
    ];

    protected $casts = [
        'request' => 'array'
    ];
}
