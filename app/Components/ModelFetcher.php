<?php

namespace App\Components;

class ModelFetcher
{
    use \App\Traits\PrivateSingleton;

    public static function fetchModel(string $modelClass, string $id): ?\System\Data\Model
    {
        $collection = $modelClass::COLLECTION;
        $path = $collection . "/" . $id;
        $response = \App\Components\Connection::send(\App\Components\Connection::METHOD_GET, $path);

        if ($response && $response['result']) {
            return new $modelClass($response['result']);
        }

        return null;
    }
}
