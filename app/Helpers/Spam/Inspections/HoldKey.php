<?php

namespace App\Helpers\Spam\Inspections;

class HoldKey {

    public function detect(string $string)
    {
        if (preg_match('~(.)\\1{4,}~', $string)) {
            throw new \Exception('Hold key detected!');
        }
    }

}