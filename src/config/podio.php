<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 7/7/17
 * Time: 5:42 PM
 */

return [
    /**
     * Podio username and password
     */
    'username' => '',
    'password' => '',


    /**
     * Include Podio apps using in the application here.
     * This list of apps are using for app authentication.
     * List the type of hooks needed to included if needed or keep empty.
     */
    'app_auth' => [
        'app_name' => [
            'app_id' => 11,
            'app_secret' => '',
            'hook_types' => [
                'item.create',
                'item.update',
            ]
        ],
    ],


    /**
     * Include multiple API Keys here.
     * This is using for rate-limit handling.
     */
    'client_api' => [
        [
            'id' => '',
            'secret' => '',
        ],
    ]
];