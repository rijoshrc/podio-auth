# Laravel-Podio Auth

Laravel-Podio library which includes
* [Username and password flow](https://developers.podio.com/authentication/username_password)
* [App authentication flow](https://developers.podio.com/authentication/app_auth)
* Podio API library
* Podio rate-limit handling
* Podio app hook handling




### Prerequisites
* php: ^5.3.0
* laravel/framework: ^5.2
* podio/podio-php: ^4.3

### Installing

1. Install package
   ```
   composer require rijosh/podio-auth
   ``` 
2. Include the `PodioAuthServiceProvider` in `config/app.php` provider list
   ```
   PodioAuth\PodioAuthServiceProvider::class
   ``` 
3. run `publish` command
   ```
   php artisan vendor:publish
   ``` 
   This will generate migrations required for the package.
   
4. Create config file `podio.php` and add the following code:
   ```
   return [
       /**
        * Podio username and password.
        * This will be using for user authentication (Username and password flow).
        */
       'username' => '',
       'password' => '',
   
   
       /**
        * Include Podio apps details here.
        * This will be using for app authentication (App authentication flow).
        * List the type of hooks if needed.
        */
       'app_auth' => [
           'app_name' => [
               'app_id' =>,
               'app_secret' => '',
               'hook_types' => []
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
   ```
5. Update `podio.php` with configuration data.
6. Run the following commands    
   ```
   * php artisan migrate
   * php artisan sync:api
   
   ``` 


## Code Examples

Use package library for authentication and Podio API

```
 <?php
 
 namespace App\Http\Controllers;
 
 
 use PodioAuth\Controllers\PodioAuth;
 use PodioAuth\Repositories\Podio;
 
 class TestController extends Controller
 {
     public function getTest()
     {
         PodioAuth::podioUserAuth(); // username-password authentication
         PodioAuth::podioAppAuth(12344); // App authentication
         PodioAuth::podioAppAuthWithName("name"); // Authenticate app with name specified in config/podio.php
         Podio::PodioApp_get(123456); // Get Podio app details
     }
 }
```

## Contributing

Contributions to Podio Auth library are welcome.