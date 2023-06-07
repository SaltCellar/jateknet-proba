<?php

namespace App\Models;

class Product extends \System\Data\Model
{
    public const COLLECTION = "products";

    public string   $name;
    public string   $desc;
    public int      $price;
}
