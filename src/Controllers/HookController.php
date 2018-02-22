<?php
/**
 * Created by PhpStorm.
 * User: rijosh
 * Date: 21/2/18
 * Time: 2:24 PM
 */

namespace PodioAuth\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use PodioAuth\Models\PodioHook;
use PodioAuth\Models\PodioRequest;
use PodioAuth\Repositories\Podio;

class HookController extends Controller
{

    /**
     * Get all hooks from Podio app.
     * Verify inactive hooks.
     */
    public function checkExistingHooks()
    {
        foreach (config("podio.app_auth") as $app) {
            $hooks = Podio::Hooks_get_hooks("app", (int)$app["app_id"]);
            foreach ($hooks as $hook) {
                if ($hook["status"] !== "active") {
                    Podio::PodioHook_verify($hook["hook_id"]);
                }
            }
        }
    }

    /**
     * Add hook to Podio apps.
     * App details are fetching from config file podio.php.
     * Ignore if hook is already added.
     */
    public function getCreate()
    {
        $ref_type = "app";
        foreach (config("podio.app_auth") as $app_name => $app) {
            PodioAuth::podioAppAuth($app["app_id"]);
            $url = URL::route("hook", ['app_id' => $app["app_id"]]);
            $ref_id = $app["app_id"];
            foreach ($app["hook_types"] as $hook_type) {
                $hookModel = PodioHook::whereRefId($ref_id)
                    ->whereRefType($ref_type)
                    ->whereType($hook_type)
                    ->whereUrl($url)
                    ->first();


                if (!$hookModel) {
                    $attribute = array('url' => $url, 'type' => $hook_type);
                    $hookCreateData = Podio::PodioHook_create($ref_type, $ref_id, $attribute);
                    Podio::PodioHook_verify($hookCreateData['hook_id']);
                    PodioHook::create(
                        [
                            'hook_id' => $hookCreateData['hook_id'],
                            'ref_id' => $ref_id,
                            'ref_type' => $ref_type,
                            'url' => $url,
                            'type' => $hook_type,
                        ]
                    );
                } else {
                    Log::info("Hooks already exist");
                }
            }
        }
    }

    /**
     * Remove hooks from Podio apps by matching the hook url.
     * This won't remove any data from database.
     */
    public function getRemove()
    {
        try {
            foreach (config("podio.app_auth") as $app_name => $app) {
                PodioAuth::podioAppAuth($app["app_id"]);
                $url = URL::route("hook", ['app_id' => $app["app_id"]]);
                $hooks = Podio::PodioHook_get_for("app", $app["app_id"]);
                foreach ($hooks as $hook) {
                    if ($url == $hook["url"]) {
                        Podio::PodioHook_delete($hook["hook_id"]);
                    }
                }
            }
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * Disable all hooks added to the Podio apps.
     * Fetch the details from the table and remove from both podio and table.
     */
    public function getDisable()
    {
        try {
            $hookModels = PodioHook::all();
            foreach ($hookModels as $hook) {
                PodioAuth::podioAppAuth($hook->app_id);
                Podio::PodioHook_delete($hook->hook_id);
                $hook->delete();
            }
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * The hook urls added the apps are pointing to this function.
     * Verify hooks for the first time.
     * All other types of hooks are moved to table and handled asynchronously.
     * @param Request $request
     * @param $app_id
     */
    public function podioHookHandle(Request $request, $app_id)
    {
        try {
            if ($request->isMethod('post')) {
                PodioAuth::podioAppAuth($app_id);
                switch ($request->input('type')) {
                    case 'hook.verify':
                        if ($request->input('code') && $request->input('hook_id')) {
                            Podio::PodioHook_validate($request->input('hook_id'), ['code' => $request->input('code')]);
                            Podio::PodioHook_verify($request->input('hook_id'));
                        }
                        break;
                    default:
                        $hook = PodioRequest::create(
                            [
                                'request' => $request->all(),
                                'app_id' => $app_id,
                            ]
                        );
                        $url = URL::route('process_hook', $hook->id);
                        $cmd = " wget -O /dev/null -o /dev/null -qb -t 1 --no-check-certificate " . '"' . $url . '"';
                        shell_exec($cmd);
                        break;
                }
            }
        } catch (\Exception $exception) {
            Log::info($exception);
        }
    }

//    public function processHook($id)
//    {
//        $request = PodioRequest::whereId($id)->first();
//        if ($request) {
//            $appId = $request->app_id;
//            $hook = $request->request;
//
//            //Process hook
//
//            $request->processed = 1;
//            $request->save();
//        }
//    }
}