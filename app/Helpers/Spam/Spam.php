<?php

namespace App\Helpers\Spam;

use App\Helpers\Spam\Inspections\HoldKey;
use App\Helpers\Spam\Inspections\InvalidKeywords;

class Spam
{
    protected $inspections = [
        HoldKey::class,
        InvalidKeywords::class,
    ];

    public function detect(string $string)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($string);
        }

        return false;
    }
}
