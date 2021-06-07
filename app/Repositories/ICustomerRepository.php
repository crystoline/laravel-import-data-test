<?php


namespace App\Repositories;


interface ICustomerRepository extends IRepository
{

    public function createUniqueCustomer(array $data);
}
