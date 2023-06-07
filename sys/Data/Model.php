<?php

namespace System\Data;

class Model
{
    public function __construct(array $attributes)
    {
        // TODO: Gyorsan egy unsafe töltést használok, Validálni és kasztolni is kellene...
        foreach ($attributes as $attributeName => $attributeValue) {
            $this->$attributeName = $attributeValue;
        }
    }
    // TODO: Az aktív rekord és query builder-t itt kellene implementálni vagy felvenni.
}
