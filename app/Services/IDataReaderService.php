<?php


namespace App\Services;


interface IDataReaderService
{
    public function loadFile(string $path, bool $asArray = false);
    public function get(): \Generator;

    public function getCurrentIndex() : int ;
}
