<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('uniqueUser', function ($attribute, $value, $parameters, $validator) {
            $value_array = explode("-", $value);
            $items = DB::select("SELECT count(*) as aggregate FROM users
                      WHERE nom ='$value_array[0]' AND prenom='$value_array[1]'
                       AND niveau_engagement_id='$value_array[2]'
                       AND niveau_engagement_id='$value_array[3]' ");
            $number=$items[0]->aggregate;
            if ($number > 0) {
                return false;
            } else {
                return true;
            }
        });
    }
}
