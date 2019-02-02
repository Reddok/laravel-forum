<?php

namespace App\Helpers\Spam\Inspections;

interface InspectionInterface
{
    public function detect(string $body);
}
