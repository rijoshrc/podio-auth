<?php
/**
 * Created by PhpStorm.
 * User: rijosh
 * Date: 15/5/17
 * Time: 1:48 PM
 */

namespace PodioAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use PodioAuth\Models\PodioApi;
use PodioAuth\Models\PodioAppAuth;
use PodioAuth\Repositories\Podio;

class PodioAuth extends Controller
{
    /**
     * Podio authenticate with username and password.
     * @return bool
     */
    public static function podioUserAuth()
    {
        try {
            self::podioSetup();
            $client = PodioApi::whereCurrent(1)->first();
            if ($client->refresh_token) {
                Log::info("AUTHENTICATE WITH REFRESH TOKEN: " . $client->id);
                return \Podio::authenticate('refresh_token', array('refresh_token' => $client->refresh_token));
            } else {
                Log::info("RE AUTHENTICATE WITH PASSWORD");
                $auth = \Podio::authenticate_with_password(Config::get('podio.username'), Config::get('podio.password'));
                $client->refresh_token = \Podio::$oauth->refresh_token;
                $client->save();
                return $auth;
            }
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Podio setup
     */
    public static function podioSetup()
    {
        $client = PodioApi::whereCurrent(1)->first();
        if (!$client) {
            $c = PodioApi::first();
            $c->current = 1;
            $c->save();
            $client = PodioApi::whereCurrent(1)->first();
        }
        \Podio::setup($client->client_id, $client->client_secret, array(
            'session_manager' => 'PodioAuth\Controllers\PodioBrowserSession'
        ));
    }

    /**
     * App Authentication
     * Authenticate with app name specified in Podio.php
     * Get app config from DB and authenticate.
     * @param $name - App name
     * @return bool
     */
    public static function podioAppAuthWithName($name)
    {
        $app = PodioAppAuth::whereAppName($name)->first();
        if ($app) {
            self::podioSetup();
            return \Podio::authenticate_with_app($app->app_id, $app->app_secret);
        } else return false;
    }

    /**
     * App Authentication
     * Authenticate using app id
     * @param $app_id
     * @return bool
     */
    public static function podioAppAuth($app_id)
    {
        $app = PodioAppAuth::whereAppId($app_id)->first();
        if ($app) {
            self::podioSetup();
            return \Podio::authenticate_with_app($app->app_id, $app->app_secret);
        } else return false;
    }
}