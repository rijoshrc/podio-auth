<?php

namespace App\Modules\Repo;

use App\Api;
use App\AppAuth;
use App\Http\Controllers\PodioAuth;
use Illuminate\Support\Facades\Log;

/**
 * Podio rest functions and rate limit handling are defined.
 * Created by PhpStorm.
 * User: rijosh
 * Date: 15/5/17
 * Time: 6:17 PM
 */
class Podio
{
    const limit = 100;

    /**
     * Check if rate limit remaining is less than limit.
     * Then switch the api.
     */
    public static function rate_limit_check()
    {
        $remaining = \Podio::rate_limit_remaining();
        if ($remaining < self::limit) {
            Log::info('LIMIT REACHED; SWITCHING');
            // Switch api
            self::switch_api();
        }
    }

    /**
     * Switch the current api to least updated.
     * Re authenticate.
     */
    public static function switch_api()
    {
        $currentApi = Api::whereCurrent(1)->first();
        $currentApi->current = 0;
        $currentApi->save();

        $api = Api::orderBy('updated_at', 'asc')
            ->where('current', '!=', 1)->first();
        $api->current = 1;
        $api->save();

        self::reAuth();
    }

    /**
     * Re authenticate with new api keys.
     * Check the last authentication used.
     * Authenticate according to the type of authentication used.
     */
    public static function reAuth()
    {
        $authType = \Podio::$auth_type;
        switch ($authType['type']) {
            case 'app':
                $appAuth = AppAuth::whereAppId($authType['identifier'])->first();
                Log::info('RE AUTHENTICATE WITH APP:' . $appAuth->app_name);
                if ($appAuth) {
                    PodioAuth::podioAppAuth($appAuth->app_name);
                }
                break;
            default:
                PodioAuth::podioUserAuth();
        }
    }

    /**
     * Podio get item.
     * @param $item_id - Podio item id.
     * @return mixed - item data.
     */
    public static function PodioItem_get($item_id)
    {
        try {
            self::rate_limit_check();
            return \Podio::get("/item/{$item_id}")->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Get podio item field values.
     * @param $item_id - Podio item id.
     * @param $field_id - Podio item field id.
     * @return mixed - field data.
     */
    public static function PodioItem_get_field_value($item_id, $field_id)
    {
        try {
            self::rate_limit_check();
            return \Podio::get("/item/{$item_id}/value/{$field_id}")->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Podio get app.
     * @param $app_id - Podio app id.
     * @return mixed
     */
    public static function PodioApp_get($app_id)
    {
        try {
            self::rate_limit_check();
            return \Podio::get("/app/{$app_id}")->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Podio filter items from application
     * @param $app_id - Podio app id.
     * @param array $attributes
     * @param array $options
     * @return mixed
     */
    public static function PodioItem_filter($app_id, $attributes = [], $options = [])
    {
        try {
            self::rate_limit_check();
            return \Podio::post("/item/app/{$app_id}/filter/", $attributes, $options)->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }


    /**
     * Create Podio hook.
     * @param $ref_type - Type: app,space,task etc
     * @param $ref_id - reference item id.
     * @param array $attributes
     * @return mixed
     */
    public static function PodioHook_create($ref_type, $ref_id, $attributes = [])
    {
        try {
            self::rate_limit_check();
            return \Podio::post("/hook/{$ref_type}/{$ref_id}/", $attributes)->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Verify Podio hooks
     * @param $hook_id
     * @return mixed
     */
    public static function PodioHook_verify($hook_id)
    {
        try {
            self::rate_limit_check();
            return \Podio::post("/hook/{$hook_id}/verify/request")->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Remove Podio hook.
     * @param $hook_id
     * @return mixed
     */
    public static function PodioHook_delete($hook_id)
    {
        try {
            self::rate_limit_check();
            return \Podio::delete("/hook/{$hook_id}")->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Validate Podio hook
     * @param $hook_id - Podio hook id.
     * @param array $attributes
     * @return mixed
     */
    public static function PodioHook_validate($hook_id, $attributes = [])
    {
        try {
            self::rate_limit_check();
            return \Podio::post("/hook/{$hook_id}/verify/validate", $attributes)->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Update Podio item field values.
     * @param $item_id
     * @param array $attributes
     * @param array $options
     * @return mixed
     */
    public static function PodioItem_update($item_id, $attributes = [], $options = [])
    {
        try {
            self::rate_limit_check();
            return \Podio::put("/item/{$item_id}", $attributes, $options)->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Get podio item revision difference
     * @param $item_id
     * @param $revision_from
     * @param $revision_to
     * @return mixed
     */
    public static function PodioItemDiff_get_for($item_id, $revision_from, $revision_to)
    {
        try {
            self::rate_limit_check();
            return \Podio::get("/item/{$item_id}/revision/{$revision_from}/{$revision_to}")->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Get item revision details.
     * @param $item_id
     * @param $revision
     * @return mixed
     */
    public static function PodioItemRevision_get($item_id, $revision)
    {
        try {
            self::rate_limit_check();
            return \Podio::get("/item/{$item_id}/revision/{$revision}")->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Podio item field update.
     * @param $item_id
     * @param $field_id
     * @param array $attributes
     * @param array $options
     * @return mixed
     */
    public static function PodioItemField_update($item_id, $field_id, $attributes = array(), $options = array())
    {
        try {
            self::rate_limit_check();
            return \Podio::put("/item/{$item_id}/value/{$field_id}", $attributes, $options)->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Podio search in application.
     * @param $app_id
     * @param array $attributes
     * @return mixed
     */
    public static function PodioSearchResult_app($app_id, $attributes = array())
    {
        try {
            self::rate_limit_check();
            return \Podio::post("/search/app/{$app_id}/", $attributes)->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Get Podio file using file id.
     * @param $file_id - Podio file id.
     * @return mixed - Podio file data.
     */
    public static function PodioFile_get($file_id)
    {
        try {
            self::rate_limit_check();
            return \PodioFile::get($file_id);
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Get Podio comments from an object.
     * @param $type
     * @param $id
     * @return mixed
     */
    public static function PodioComment_get_for($type, $id)
    {
        try {
            self::rate_limit_check();
            return \Podio::get("/comment/{$type}/{$id}/")->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Get single comment from comment id
     * @param $comment_id
     * @return mixed
     */
    public static function PodioComment_get($comment_id)
    {
        try {
            self::rate_limit_check();
            return \Podio::get("/comment/{$comment_id}")->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    public static function PodioComment_create($type, $id, $attributes, $option = [])
    {
        try {
            self::rate_limit_check();
            return \Podio::post("/comment/{$type}/{$id}/", $attributes)->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    public static function PodioItem_create($app_id, $attributes = [], $options = [])
    {
        try {
            self::rate_limit_check();
            return \Podio::post("/item/app/{$app_id}/", $attributes, $options)->json_body();
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Upload a file into Podio, return File Id
     * @param $filePath
     * @param $filename
     * @return mixed
     */
    public static function PodioFile_upload($filePath, $filename)
    {
        try {
            self::rate_limit_check();
            return \PodioFile::upload($filePath, $filename);
        } catch (\PodioBadRequestError $exception) {
            Log::info($exception);
        }
    }

    /**
     * Podio item remove.
     * @param $item_id
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public static function PodioItem_delete($item_id, $attributes = [], $options = [])
    {
        try {
            self::rate_limit_check();
            return \Podio::delete("/item/{$item_id}", $attributes, $options);
        } catch (\PodioBadRequestError $exception) {
            Log::info($exception);
        }
    }
}