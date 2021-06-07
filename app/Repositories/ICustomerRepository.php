<?php


namespace App\Repositories;


use App\Models\Customer;

interface ICustomerRepository extends IRepository
{

    public function createUniqueCustomer(array $data) : ?Customer;
}
