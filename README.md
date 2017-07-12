# Laravel-Podio Auth

Laravel-Podio library which includes
* [Username and password flow](https://developers.podio.com/authentication/username_password)
* [App authentication flow](https://developers.podio.com/authentication/app_auth)
* Podio API library
* Podio rate-limit



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
   This will create `podio.php` file in `config` directory.
4. Update `podio.php` with configuration data.
5. Run the following commands    
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