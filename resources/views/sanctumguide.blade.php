{{-- Authentication using sanctum --}}

1. first you need to run command based of laravel documentation.
    a. composer require laravel/sanctum
    b. php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

    then go to config/sanctum.php: in this page you can setup your domain.
        "'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1,127.0.0.1:8000,::1')),"

    then go to config/session: this is for the setup of your domain.
        "'domain' => env('SESSION_DOMAIN', null)," will look like this:  "'domain' => env('SESSION_DOMAIN', example.com),"

2. then run php artisan migrate to create the table for personal_access_tokens.

3. then go to app/Http/Kernel.php,
    add this this line of code to the 'api'. EnsureFrontendRequestsAreStateful::class,
        will look like this:
        'api' => [
            EnsureFrontendRequestsAreStateful::class,
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        then add this code:
        use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
4. then setup the base url go to vendor/laravel/sanctum/src/SanctumServiceProvider.
    go to protected function defineRoutes().

    then go to sanctum.php. just add this line off code under 'middleware':    'prefix' => 'api',

5. go to config/cors.php just enable this:
'supports_credentials' => false, will become 'supports_credentials' => true,

6. then go to your http_service.js then add this lines of code:
    Api.defaults.withCredentials = true;
    export default Api;
