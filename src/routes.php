<?php

Route::get('podio/auth', function () {
    echo 'Podio authentication';
});
Route::get('hook/create', 'PodioAuth\Controllers\HookController@getCreate');
Route::get('hook/remove', 'PodioAuth\Controllers\HookController@getRemove');
Route::get('hook/disable', 'PodioAuth\Controllers\HookController@getDisable');
Route::post('handle/{app_id}/hook', 'PodioAuth\Controllers\HookController@podioHookHandle')->name("hook");
Route::get('cron/hook', 'PodioAuth\Controllers\HookController@checkExistingHooks')->name("hook_cron");
//Route::get('process/{id}/hook', 'PodioAuth\Controllers\HookController@processHook')->name('process_hook');