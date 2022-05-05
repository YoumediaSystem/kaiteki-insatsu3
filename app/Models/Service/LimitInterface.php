<?php

namespace App\Models\Service;

class LimitInterface
{
    public function __construct() {

    }

    public function getObject($client_code = 'taiyou', $product_code = 'offset') {

        if ($client_code == 'pico')
            return new \App\Models\Service\Limit\PicoLimit();

        return new \App\Models\Service\LimitDate(); // default taiyou
    }
}