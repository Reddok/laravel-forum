<?php

namespace App\Helpers\Spam\Inspections;

class InvalidKeywords {

    protected $invalidKeywords = [
        'yahoo customer support'
    ];

    public function detect(string $string)
    {
        foreach ($this->invalidKeywords as $keyword) {
            if (stripos($string, $keyword) !== false) {
                throw new \Exception('Invalid keyword used in text!');
            }
        }
    }

}