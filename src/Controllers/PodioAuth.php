<?php
/**
 * Created by PhpStorm.
 * User: rijosh
 * Date: 15/5/17
 * Time: 1:48 PM
 */

namespace PodioAuth\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use PodioAuth\Models\Api;
use PodioAuth\Models\AppAuth;

class PodioAuth extends Controller
{
    /**
     * Podio authenticate with username and password.
     * @return bool
     */
    public static function podioUserAuth()
    {
        try {
            // setup podio api
            self::podioSetup();
            // get the current api using
            $client = Api::whereCurrent(1)->first();
            // if an api is currently using and it has a refresh token
            if ($client && $client->refresh_token) {
                // authenticate with refresh token
                Log::info("AUTHENTICATE WITH REFRESH TOKEN: " . $client->id);
                return \Podio::authenticate('refresh_token', array('refresh_token' => $client->refresh_token));
            } else {
                // if no refresh token
                // authenticate with username and password
                Log::info("RE AUTHENTICATE WITH PASSWORD");
                $auth = \Podio::authenticate_with_password(config('podio.username'), config('podio.password'));
                // get the refresh token and save to the table
                $client->refresh_token = \Podio::$oauth->refresh_token;
                $client->save();
                return $auth;
            }
        } catch (\PodioRateLimitError $error) {
            // when rate limit error occur, switch the current api to least used api
            // get the current api
            $currentApi = Api::whereCurrent(1)->first();
            // set current flag as 0
            $currentApi->current = 0;
            $currentApi->save();
            // get the next, least updated api not current row
            $nextApi = Api::orderBy('updated_at', 'asc')
                ->where('current', '!=', 1)->first();
            // set current flag as 1
            $nextApi->current = 1;
            $nextApi->save();
            // setup podio api
            self::podioSetup();
            // authenticate using username and password
            $auth = \Podio::authenticate_with_password(config('podio.username'), config('podio.password'));
            // update the refresh token of the next api
            $nextApi->refresh_token = \Podio::$oauth->refresh_token;
            $nextApi->save();
            log::error($error->getMessage());
            return $auth;
        } catch (\Exception $e) {
            // error will occure mostly because of the expired refresh token
            self::podioSetup();
            // get the current api row
            $client = Api::whereCurrent(1)->first();
            // authenticate using username and password
            $auth = \Podio::authenticate_with_password(config('podio.username'), config('podio.password'));
            // get the new refresh token
            $client->refresh_token = \Podio::$oauth->refresh_token;
            // save to the row
            $client->save();
            log::error($e);
            return $auth;
        }
    }

    /**
     * Podio setup
     */
    public static function podioSetup()
    {
        // get the current api row
        $client = Api::whereCurrent(1)->first();
        // if no api set as current
        if (!$client) {
            // set the first row as current
            $c = Api::first();
            $c->current = 1;
            $c->save();
            $client = Api::whereCurrent(1)->first();
        }
        // setup podio using the api
        \Podio::setup($client->client_id, $client->client_secret, array(
            "session_manager" => "PodioBrowserSession"
        ));
    }

    /**
     * Get app config from DB and authenticate.
     * @param $name - App name
     * @return bool
     */
    public static function podioAppAuthWithName($name)
    {
        $app = AppAuth::whereAppName($name)->first();
        if ($app) {
            self::podioSetup();
            return \Podio::authenticate_with_app($app->app_id, $app->app_secret);
        } else return false;
    }

    /**
     * App auth using id
     * @param $app_id
     * @return bool
     */
    public static function podioAppAuth($app_id)
    {
        $app = AppAuth::whereAppId($app_id)->first();
        if ($app) {
            self::podioSetup();
            return \Podio::authenticate_with_app($app->app_id, $app->app_secret);
        } else return false;
    }
}