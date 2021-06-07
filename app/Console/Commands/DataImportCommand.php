<?php

namespace App\Console\Commands;

use App\Repositories\ICustomerRepository;
use App\Services\IDataImportService;
use App\Services\IDataReaderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class DataImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:import {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import contents';

    /**
     * Create a new command instance.
     *
     * @param ICustomerRepository $customerRepository
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $filename = $this->argument('filename');
        if(empty($filename) || !file_exists($filename)){
            $this->error("File '{$filename}' does not exist.");
            return 0;
        }

        $ext =  pathinfo($filename, PATHINFO_EXTENSION);
        $allowed_extensions = config('app.data_import.allowed_extensions', ['json']);
        if(!in_array($ext, $allowed_extensions)){
            $this->error("The specified file '{$filename}' is not supported.");
            return ;
        }


        /** @var IDataImportService $data_import_service */
        $data_import_service = App::make(IDataImportService::class, ['file_ext' => $ext]);
       //dump($data_import_service);
        $data_import_service->onDataImportBegin(function ($message){
            if(!empty($message['index'])){
                $this->alert('Starting data import at index: '. ($message['index']));
            }
        });

        $data_import_service->onItemImportFailed(function ($message){
            $index = $message['index']??0;
            $errors = $message['errors']??[];
            $this->info('Customer data failed validation. at index: '.$index);
            $this->error(implode("\n", $errors));
        });

        $data_import_service->onItemImportComplete(function ($message){
            $index = $message['index']??0;
            $this->alert('Customer data import successful. at index: '.$index);
        });

        $data_import_service->onDataImportEnd(function ($message){
            $this->info( "Data import done");
        });


        $data_import_service->import($filename);


    }


}
