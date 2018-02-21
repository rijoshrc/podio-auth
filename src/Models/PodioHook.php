<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PodioHook extends Model
{
    protected $fillable = [
        'ref_id',
        'ref_type',
        'hook_id',
        'type',
        'url'
    ];
}
