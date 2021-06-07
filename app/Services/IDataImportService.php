<?php


namespace App\Services;


interface IDataImportService
{

    public function import(string $filename);


    public function onDataImportBegin(Callable $callback);
    public function onDataImportEnd(Callable $callback);
    public function onItemImportComplete(Callable $callback);
    public function onItemImportFailed(Callable $callback);
}
