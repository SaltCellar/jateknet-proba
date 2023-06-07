<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

const PATH_APP      = __DIR__ . DIRECTORY_SEPARATOR . 'app';
const PATH_STORAGE  = __DIR__ . DIRECTORY_SEPARATOR . 'storage';

const TEST_CONNECTION = true;

/*
 * .env -ben lévő környezeti változók - A tesztben konstansba hozom ki a projektben.
 * getenv("REMOTE_URL"), getenv("REMOTE_TOKEN"),
 */

const REMOTE_URL    = "https://api.test/";
const REMOTE_TOKEN  = "hh4h98fh48ehdhd983h3838h";

final class App
{

    public function __construct()
    {

        // TODO: Feltételezük hogy a szerver JSON választ-ad és a válasz entitásban a modell a "result" attribútumban van.

        // Product.:
        $response = \App\Components\Connection::send(\App\Components\Connection::METHOD_GET, "products/1657047");

        if ($response && isset($response['result'])) {
            $productModel = new \App\Models\Product($response['result']);
        }

        // ProductCategory.:
        $response = \App\Components\Connection::send(\App\Components\Connection::METHOD_GET, "categories/345");

        if ($response && isset($response['result'])) {
            $productCategoryModel = new \App\Models\ProductCategory($response['result']);
        }

        // TODO: A feladat úgy szolt hogy külön class ba legyen.: A fecthert külön modellenként lehet hívni.

        $productModel           = \App\Components\ModelFetcher::fetchModel(\App\Models\Product::class, "1657047");
        $productCategoryModel   = \App\Components\ModelFetcher::fetchModel(\App\Models\ProductCategory::class, "345");

        // Test...
        var_dump($productModel);

    }

}

(new App());
