<?php


namespace App\Repositories;


use App\Models\Customer;

class CustomerRepository extends BaseRepository implements ICustomerRepository
{

    /**
     * CustomerRepository constructor.
     * @param Customer $customer
     */
    public function __construct(Customer $customer )
    {
        parent::__constructor($customer);
    }

    public function createCustomer(array $data) : ?Customer
    {
        // TODO: Implement createUniqueCustomer() method.
        dump(@$data['email']);
        /** @var Customer $customer */
        if(!empty($data['date_of_birth' ])){
            $data['date_of_birth' ] =  new \Carbon\Carbon(str_replace('/', '-', $data['date_of_birth' ]));
        }
        $customer = $this->create($data);
        if(!empty($data['card']))
        $customer->cards()->create($data['card']);
        return $customer;
    }
}
