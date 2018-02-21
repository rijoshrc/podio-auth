<?php

Route::get('podio/auth', function () {
    echo 'Podio authentication';
});
Route::post('handle/{app_id}/hook', 'HookController@podioHookHandle')->name("hook");
Route::get('cron/hook', 'HookController@checkExistingHooks')->name("hook_cron");
//Route::get('process/{id}/hook', 'HookController@processHook')->name('process_hook');