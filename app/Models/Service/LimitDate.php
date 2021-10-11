<?php

namespace App\Models\Service;

class LimitDate
{

    function getUploadBorderDate($type = 'text') {
        // イベント合わせ入稿期限
        $DT_border = new \Datetime('next wednesday');

        if ($type == 'object') return $DT_border;

        return $DT_border->format('Y/n/j');
    }

    function getPaymentLimitDate($type = 'text') {
        // イベント合わせ入金期限
        $DT_border = new \Datetime('next wednesday');

        if ($type == 'object') return $DT_border;

        return $DT_border->format('Y/n/j 10:00:00');
    }

    function getBorderEventDate($type = 'text') {
        // イベント合わせ入稿期限
        $DT_border = new \Datetime('next wednesday');
        $DT_border->modify('+3days'); // saturday

        if ($type == 'object') return $DT_border;

        return $DT_border->format('Y/n/j');
    }

    function getBorderLaterDate($type = 'text') {
        // 納品希望日上限
        $DT_border_later = new \Datetime();
        $DT_border_later->modify('+60 days');

        if ($type == 'object') return $DT_border_later;

        return $DT_border_later->format('Y/n/j');
    }

}
