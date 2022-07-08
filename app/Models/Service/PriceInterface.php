<?php

namespace App\Models\Service;

class PriceInterface
{
    public function __construct() {

    }

    public function getObject($client_code = 'taiyou', $product_code = 'offset') {

        if ($client_code == 'pico')
            return new \App\Models\Service\Price\PicoPrice();

        if ($client_code == 'kanbi')
            return new \App\Models\Service\Price\KanbiPrice();

        return new \App\Models\Service\Price(); // default taiyou
    }
}