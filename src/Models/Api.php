<?php
/**
 * Created by PhpStorm.
 * User: rijosh
 * Date: 15/5/17
 * Time: 5:22 PM
 */

namespace PodioAuth\Models;


use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $fillable = [
        'client_id', 'client_secret', 'current', 'refresh_token'
    ];

    protected $table = 'api';
}