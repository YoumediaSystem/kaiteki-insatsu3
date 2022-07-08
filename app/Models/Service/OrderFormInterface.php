<?php

namespace App\Models\Service;

class OrderFormInterface
{
    public function __construct() {

    }

    public function getObject($client_code = 'taiyou', $product_code = 'offset') {

        if ($client_code == 'pico')
            return new \App\Models\Service\OrderForm\PicoForm();

        if ($client_code == 'kanbi')
            return new \App\Models\Service\OrderForm\KanbiForm();

        return new \App\Models\Service\OrderForm(); // default taiyou
    }
}