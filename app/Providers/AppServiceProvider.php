<?php

namespace App\Providers;

use App\Repositories\CustomerRepository;
use App\Repositories\ICustomerRepository;
use App\Services\CsvDataReaderService;
use App\Services\DataImportService;
use App\Services\IDataImportService;
use App\Services\IDataReaderService;
use App\Services\JsonDataReaderService;
use DateTime;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
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


        $this->app->singleton(ICustomerRepository::class, CustomerRepository::class);
        $this->app->bind(IDataReaderService::class, function ( $app, $params){
            switch (@$params['file_ext']){
                case 'csv':
                    return new CsvDataReaderService();
                case 'json':
                default: return new JsonDataReaderService();
            }
        });


        $this->app->bind(IDataImportService::class, function (Application $app, $params){
            return new DataImportService(
                $app->make(IDataReaderService::class, $params),
                $app->make(ICustomerRepository::class, $params),
                Config::get('app.data_import.validation_rules', []),
                Config::get('app.data_import.validation_messages', [])
            );
        });




    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('age_btw', function($attribute, $value, $parameters)
        {
            $minAge = $parameters[0]?? 0;
            $maxAge = $parameters[1]?? null;
            $age = \Carbon\Carbon::now()->diff(new \Carbon\Carbon(str_replace('/', '-', $value)))->y;
            return ($age >= $minAge && ($maxAge == null || $age <= $maxAge));
        });
    }
}
