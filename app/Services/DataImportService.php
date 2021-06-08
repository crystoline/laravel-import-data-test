<?php


namespace App\Services;


use App\Repositories\ICustomerRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class DataImportService implements IDataImportService
{
    /**
     * @var IDataReaderService
     */
    private IDataReaderService $dataReaderService;
    /**
     * @var callable
     */
    private $onItemImportFailedCallback;
    /**
     * @var callable
     */
    private $onDataImportBeginCallback;
    /**
     * @var callable
     */
    private $onDataImportEndCallback;
    /**
     * @var callable
     */
    private $onItemImportCompleteCallback;
    /**
     * @var ICustomerRepository
     */
    private ICustomerRepository $customerRepository;
    /**
     * @var array
     */
    private array $validationRules;
    /**
     * @var array
     */
    private array $validationMessages;

    /**
     *
     * DataImportService constructor.
     * @param IDataReaderService $dataReaderService
     * @param ICustomerRepository $customerRepository
     * @param array $validationRules
     * @param array $validationMessages
     */
    public function __construct(IDataReaderService $dataReaderService, ICustomerRepository $customerRepository, array  $validationRules,array  $validationMessages)
    {
        $this->dataReaderService = $dataReaderService;
        $this->customerRepository = $customerRepository;
        $this->validationRules = $validationRules;
        $this->validationMessages = $validationMessages;
    }

    public function import(string $filename)
    {
        $this->dataReaderService->loadFile($filename, true);

        $last_index = Cache::get('DATA_IMPORT_INDEX', -1);

        $this->doCallback($this->onDataImportBeginCallback,['index' => ($last_index+1) ]); //callback

        foreach ( $this->dataReaderService->get() as $data ){
            $index = $this->dataReaderService->getCurrentIndex();
            if($last_index + 1 == $index){

                //Validate
                $validator = Validator::make($data, $this->validationRules, $this->validationMessages);

                if ($validator->fails()) {
                    $this->doCallback($this->onItemImportFailedCallback,['index' => $index, 'data' => $data, 'errors' => $validator->errors()->all()]);//callback
                    $last_index = $this->SaveProgress($index);
                    continue;
                }


                //import data
                try{

                    $customer = $this->customerRepository->createCustomer($data);
                    if($customer){
                        $this->doCallback($this->onItemImportFailedCallback,['index' => $index, 'data' => $data,  'errors' => $validator->errors()->all()]);//callback
                    }else{
                        $this->doCallback($this->onItemImportCompleteCallback, ['index' => $index, 'data' => $data]);//callback
                    }

                }catch (\Exception $exception){
                    $this->doCallback($this->onItemImportFailedCallback,['index' => $index, 'data' => $data, 'errors' => [$exception->getMessage()]]);//callback
                }


                $last_index = $this->SaveProgress($index);
            }
        }

        $this->doCallback($this->onDataImportEndCallback, ['index' => $last_index]);//callback

        Cache::pull('DATA_IMPORT_INDEX');
    }

    /**
     * @param int $index
     * @return int
     */
    public function SaveProgress(int $index): int
    {
        $last_index = $index;
        Cache::put('DATA_IMPORT_INDEX', $last_index);

        return $last_index;
    }

    public function doCallback(Callable $callback, $message){
        if(is_callable($callback)){
            call_user_func( $callback, $message);
        }
    }

    public function onDataImportBegin(callable $callback)
    {
        $this->onDataImportBeginCallback = $callback;
    }

    public function onDataImportEnd(callable $callback)
    {
        $this->onDataImportEndCallback = $callback;
    }

    public function onItemImportComplete(callable $callback)
    {
        $this->onItemImportCompleteCallback = $callback;
    }

    public function onItemImportFailed(callable $callback)
    {
        $this->onItemImportFailedCallback = $callback;
    }
}
