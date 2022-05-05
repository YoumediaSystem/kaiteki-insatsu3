<?php

namespace App\Models\Service;

class PriceInterface
{
    public function __construct() {

    }

    public function getObject($client_code = 'taiyou', $product_code = 'offset') {

        if ($client_code == 'pico')
            return new \App\Models\Service\Price\PicoPrice();

        return new \App\Models\Service\Price(); // default taiyou
    }
}