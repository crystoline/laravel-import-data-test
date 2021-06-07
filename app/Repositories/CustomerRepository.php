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

    public function createUniqueCustomer(array $data) : Customer
    {
        // TODO: Implement createUniqueCustomer() method.
        dump(@$data['email']);
    }
}
