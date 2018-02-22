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

## Using hook management module

The following urls can be used to manage Podio hooks.

* `hook/create` : Add hooks to all Podio apps listed in the config file.
* `hook/remove` : Remove all hooks added from the application independent of the entries created in `podio_hooks` table.
* `hook/disable` : Remove all hooks from Podio apps that listed in the `podio_hooks` table.
* `cron/hook` : Use this url as a cron job. It will check for inactive hooks in apps and enable them.

After adding the hooks, make sure all hooks are verified. The hook url will be `handle/{app_id}/hook`. All the hooks from Podio will be entered into the `podio_requests` table and trigger the hook processing url. This will help to process hooks asynchronously. Follow the steps to continue with the hook processing.
* Create new controller and extend `PodioAuth\Controllers\HookController`.
* Add function to process the hooks.
```
    public function processHook($id) // table row id
    {
        $request = PodioRequest::whereId($id)->first(); 
        if ($request) {
            $appId = $request->app_id;
            $hook = $request->request;

             
            /**
            * Start processing hook.
            */
            $request->is_processing = 1;
            $request->save();


            /**
            * Your code
            */


            /**
            * Finished processing hook.
            */
            $request->is_processing = 0;
            $request->is_processed = 1;
            $request->save();
        }
    }
```
* Add route for this function with name `process_hook` and method as `get`.
```
Route::get('process/{id}/hook', 'HookController@processHook')->name('process_hook');
```

## Note
All the functionalities are working depending on the configuration data in the `podio.php` file. Make sure `app_auth` and `client_api` are correctly synced to corresponding tables in database. Clear the configuration cache by running the command `php artisan config:cache`.

## Contributing

Contributions to Podio Auth library are welcome.