<?php

namespace App\Models;

class ProductCategory extends \System\Data\Model
{
    public const COLLECTION = "categories";

    public string   $name;
    public string   $desc;
}
