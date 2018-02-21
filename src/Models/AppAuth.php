<?php
/**
 * Created by PhpStorm.
 * User: rijosh
 * Date: 15/5/17
 * Time: 4:20 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class AppAuth extends Model
{
    protected $fillable = [
        'app_id', 'app_secret'
    ];

    protected $table = 'app_auth';
}