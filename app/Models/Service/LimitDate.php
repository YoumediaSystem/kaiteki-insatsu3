<?php

namespace App\Models\Service;

class LimitDate
{
    protected $limit_weekday = 'tuesday';
    protected $limit_hms = '12:00:00';
    protected $saturday_gap = 0;
    protected $limit_text = '火曜12時';
    protected $limit_youbi_text = '';

    function __construct() {

        $youbi = (new \App\Models\CommonLibrary())->getYoubiArray();

        $DT1 = new \Datetime('next '.$this->limit_weekday
        , new \DateTimezone('Asia/Tokyo'));

        $this->limit_youbi_text = $youbi[$DT1->format('w')];
        $this->limit_text = $this->limit_youbi_text.' ';


        $DT2 = new \Datetime($DT1->format('Y-m-d')
        , new \DateTimezone('Asia/Tokyo'));

        $DT2->modify('next saturday');

        $DT_diff = $DT1->diff($DT2);

        $this->saturday_gap = (int)$DT_diff->d;


        $DT1->modify($this->limit_hms);
        $this->limit_text .= $DT1->format('H').'時まで';
    }

    function getLimitText() {
        return (string)$this->limit_text;
    }

    function getLimitText4outline() {
        $text = $this->limit_text;
        $text = str_replace('(','',$text);
        $text = str_replace(') ','曜日',$text);
        $text = str_replace('まで','〆',$text);
        return $text;
    }

    function getLimitYoubiText() {
        return (string)$this->limit_youbi_text;
    }

    function getUploadBorderDate($type = 'text', $datetext = '') {
        
        // イベント合わせ入稿期限
        $b  = !empty($datetext);
        $b &= $this->checkDatetimeFormat($datetext);

        $DT_border = new \Datetime($b ? $datetext : '', new \DateTimezone('Asia/Tokyo'));

        $DT_border->modify('next '.$this->limit_weekday);

        if ($type == 'object') return $DT_border;

        return $DT_border->format('Y/n/j');
    }

    function getPaymentLimitDate($type = 'text', $datetext = '') {
        
        // イベント合わせ入金期限
        $b  = !empty($datetext);
        $b &= $this->checkDatetimeFormat($datetext);

        $DT_border = new \Datetime($b ? $datetext : '', new \DateTimezone('Asia/Tokyo'));

        $DT_border->modify('next '.$this->limit_weekday);

        if ($type == 'object') return $DT_border;

        return $DT_border->format('Y/n/j '.$this->limit_hms);
    }

    function getBorderEventDate($type = 'text', $datetext = '') {
        // イベント合わせ納品期限　最短
        $b  = !empty($datetext);
        $b &= $this->checkDatetimeFormat($datetext);

        $DT_border = new \Datetime($b ? $datetext : '', new \DateTimezone('Asia/Tokyo'));

        $DT_border->modify('next '.$this->limit_weekday);
        $DT_border->modify('+'.$this->saturday_gap.'days');

        if ($type == 'object') return $DT_border;

        return $DT_border->format('Y/n/j');
    }

    function getBorderLaterDate($type = 'text', $datetext = '') {
        // 納品希望日上限
        $DT_border_later = new \Datetime('', new \DateTimezone('Asia/Tokyo'));
        $DT_border_later->modify('+60 days');

        if ($type == 'object') return $DT_border_later;

        return $DT_border_later->format('Y/n/j');
    }

    function getLimitList4OrderForm($client_code = '') {

        $d_from = $this->getBorderEventDate();
        $d_to   = $this->getBorderLaterDate();
        $DT_to  = $this->getBorderLaterDate('object');
        $ymd_to = $DT_to->format('Ymd');

        $early_limit = (new \App\Models\DB\LimitDateList())
        ->getList4OrderForm([
            'client_code'   => $client_code,
            'date_from'     => $d_from,
            'date_to'       => $d_to
        ]);

        $a1 = [];
        $a2 = [];
        foreach ($early_limit as $row) {
            $a1[] = $row['print_up_date'];
            $a2[str_replace('-','_',$row['print_up_date'])] = $row['limit_date'];
        }

        $DT = new \Datetime($d_from.' '.$this->limit_hms
        , new \DateTimezone('Asia/Tokyo'));

        $data = []; $len = 60;
        $d_to = str_replace('/','-',$d_to);

        do {
            $ymd = $DT->format('Ymd');
            $datetext = $DT->format('Y-m-d');
            $key = str_replace('-','_',$datetext);

            if (in_array($datetext, $a1)) {
                $limit_date = $a2[$key];

            } else {
                $DT2 = new \Datetime(
                    $datetext
                    , new \DateTimezone('Asia/Tokyo'));
    
                $DT2->modify('-2days');
                $DT2->modify('last '.$this->limit_weekday.' '.$this->limit_hms);
                $limit_date = $DT2->format('Y-m-d H:i:s');
            }
            $data[$key] = $limit_date;

            $len--; $DT->modify('+1day');
            $b  = ($ymd < $ymd_to);
            $b &= (0 < $len);

        } while ($b);

        return $data;
    }

    function checkDatetimeFormat($datetime){
        return $datetime === date("Y-m-d H:i:s", strtotime($datetime));
    }

}
