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


        /** @var IDataReaderService $data_import_service */
        $data_import_service = App:: make(IDataImportService::class, ['file_ext' => 'csv']);
       dump($data_import_service);
        /** @var IDataReaderService $data_reader_service */
        $data_reader_service = App:: make(IDataReaderService::class, ['file_ext' => $ext]);

        $data_reader_service->loadFile($filename, true);

        $last_index = Cache::get('DATA_IMPORT_INDEX', -1);
        $this->alert('Starting data import at index: '. ($last_index+1));
        foreach ( $data_reader_service->get() as $data ){
            $index = $data_reader_service->getCurrentIndex();
            if($last_index + 1 == $index){
                $validator = Validator::make($data, [
                  /*  'a' => 'required',
                    'b' => 'required'*/
                ]);
                if ($validator->fails()) {
                    $this->info('Customer data failed validation.');
                    $this->error(implode("\n", $validator->errors()->all()));
                    $last_index = $this->SaveProgress($index);
                    continue;
                }
                //import data
                $this->customerRepository->createUniqueCustomer($data);


                $last_index = $this->SaveProgress($index);
            }
        }
        Cache::pull('DATA_IMPORT_INDEX');

        $this->alert('Data import complete');
    }


}
