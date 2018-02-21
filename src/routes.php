<?php

Route::get('podio/auth', function () {
    echo 'Podio authentication';
});
Route::get('hook/create', 'HookController@getCreate');
Route::get('hook/remove', 'HookController@getRemove');
Route::get('hook/disable', 'HookController@getDisable');
Route::post('handle/{app_id}/hook', 'HookController@podioHookHandle')->name("hook");
Route::get('cron/hook', 'HookController@checkExistingHooks')->name("hook_cron");
//Route::get('process/{id}/hook', 'HookController@processHook')->name('process_hook');