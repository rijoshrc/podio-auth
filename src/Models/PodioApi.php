<?php

namespace PodioAuth\Models;

use Illuminate\Database\Eloquent\Model;

class PodioApi extends Model
{
    protected $fillable = [
        'client_id', 'client_secret', 'current', 'refresh_token'
    ];
}
