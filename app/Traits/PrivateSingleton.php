<?php

namespace App\Traits;

trait PrivateSingleton
{
    private static ? self $instance = null;

    private static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            return new self();
        }
        return self::$instance;
    }

    final public function __construct()
    {
        if (!is_null(self::$instance)) {
            throw new \RuntimeException('Only one of this class can exist!');
        } else {
            self::$instance = $this;
        }
    }
}
