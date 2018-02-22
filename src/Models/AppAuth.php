<?php
/**
 * Created by PhpStorm.
 * User: rijosh
 * Date: 15/5/17
 * Time: 4:20 PM
 */

namespace PodioAuth\Models;


use Illuminate\Database\Eloquent\Model;

class AppAuth extends Model
{
    protected $fillable = [
        'app_id', 'app_secret','app_name'
    ];

    protected $table = 'app_auth';
}