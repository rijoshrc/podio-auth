<?php
/**
 * Created by PhpStorm.
 * User: rijosh
 * Date: 7/7/17
 * Time: 5:42 PM
 */

return [
    /**
     * Api keys generated from Account settings.
     * Include single key pair here.
     */
    'client_id' => '',
    'client_secret' => '',


    /**
     * Podio username and password
     */
    'username' => '',
    'password' => '',


    /**
     * Include Podio apps using in the application here.
     * This list of apps are using for app authentication.
     */
    'app_auth' => [
        'app_name' => [
            'app_id' => 11,
            'app_secret' => '',
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