<?php

namespace KdymSolucoes\LaravelBoilerplate;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LaravelBoilerplateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            \KdymSolucoes\LaravelBoilerplate\Commands\Install::class,
            \KdymSolucoes\LaravelBoilerplate\Commands\Auth::class,
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public static function routes() {
        Route::group(['prefix' => '/users'], function () {
            Route::post('/first-user', 'UsersController@storeFirstUser')->name('users.first-user');
            Route::get('/profile', 'UsersController@profile')->name('users.profile');
            Route::post('/profile', 'UsersController@updateProfile')->name('users.save-profile');
            Route::post('/check-email', 'UsersController@checkEmail')->name('users.check-email');
            Route::post('/check-profile-email', 'UsersController@checkProfileEmail')->name('users.check-profile-email');
            Route::post('/check-profile-password', 'UsersController@checkProfilePassword')->name('users.check-profile-password');
        });
        Route::resource('/users', 'UsersController');
    }
}
