<?php

namespace App\Helpers\Spam\Inspections;

interface InspectionInterface {

    function detect(string $body);

}