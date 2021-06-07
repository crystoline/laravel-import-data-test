<?php

namespace App\Providers;

use App\Repositories\CustomerRepository;
use App\Repositories\ICustomerRepository;
use App\Services\CsvDataReaderService;
use App\Services\DataImportService;
use App\Services\IDataImportService;
use App\Services\IDataReaderService;
use App\Services\JsonDataReaderService;
use Illuminate\Foundation\Application;
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


        $this->app->bind(ICustomerRepository::class, CustomerRepository::class);

        $this->app->bind(IDataImportService::class, function (Application $app, $params){
             $reader  = $app->make(IDataReaderService::class, $params);
            return new DataImportService($reader);
        } );


        $this->app->bind(IDataReaderService::class, function ( $app, $params){
           switch (@$params['file_ext']){
               case 'csv':
                   return new CsvDataReaderService();
               case 'json':
               default: return new JsonDataReaderService();
           }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
