<?php

namespace App\Models\Service;

class MatrixData
{
    protected $setvice_name = '快適印刷さん';

    public function __construct() {

        defined('MATRIX_PATH') || define('MATRIX_PATH',
            config('Paths')->writableDirectory
            .'/uploads/product_data/matrix/'
//            str_replace('/','\\',config('Paths')->writableDirectory)
//            .'\\product_data\\matrix\\'
        );
    }
    
    public function getMatrixData($param)
    {
        $session = session(); $log = '';

        if (empty($param['client_code'])
        ||  empty($param['product_code'])
//        ||  empty($param['paper_size'])
        ) return [];

        $file_place   = MATRIX_PATH.$param['client_code'].'_'.$param['product_code'];

        $file_place .= (!empty($param['paper_size']))
            ? '_'.$param['paper_size'].'.csv'
            : '.csv';

        $log .= $file_place;

        if (file_exists($file_place)) {
            $log .= '*';
            $csv = $this->getCSVfile($file_place);
            $data = $this->parseCSVindex($csv);
        }else
            $data = [];

        if (!empty($log)) $session->set('log', $log);

        return $data;
    }

    function getCSVfile($file_place) {

        $file = new \SplFileObject($file_place); 
        $file->setFlags(\SplFileObject::READ_CSV); 
    
        $data = [];
        foreach ($file as $line) {
    
            //終端の空行を除く処理　空行の場合に取れる値は後述
            if (!is_null($line[0])) $data[] = $line;
        }
    
        return $data;
    }
    
    function parseCSVindex($data) {
    
        $U8 = 'UTF-8'; $SJ = 'sjis-win';
    
        $iv = '';
        $ih = '';
    
        $index_v = [];
        $index_h = [];
        $result = [];
        $a = [];
    
        foreach($data as $key=>$val) {
    
            foreach($val as $kkey=>$vval) {
    
                $vv = mb_convert_encoding($vval, $U8, $SJ);
    
                if ($key == 0) {
                    
                    if ($kkey != 0)
                        $index_h[$kkey] = $vv;
    
                } else {
    
                    $ih = $index_h[$kkey] ?? 0;
    
                    if ($kkey == 0) {
                        $index_v[$key] = $vv;
                        $iv = $vv;
    
                        if ($key != 0 && $ih != '') $a[$ih] = [];
    
                    } elseif (!empty($ih) && !empty($iv)) {
                        $a[$ih][$iv] = $vv;
                    }
                }
            }
        }
    
        $result['index_v'] = $index_v;
        $result['index_h'] = $index_h;
        $result['data'] = $a;
    
        return $result;
    }
    
    // --- ファイル一覧取得
    
    function getFileList($dir, $ext = '') {
        
        $SPL	= new RecursiveDirectoryIterator($dir);
        $SPLi	= new RecursiveIteratorIterator($SPL);
     
        $a = [];
        foreach ($SPLi as $file) {
            
            if ($file->isFile()) {
    
                $path = $file->getPathname();
    
                if (strpos($path, '.'.$ext) !== false) $a[] = $path;
            }
        }
        return $a;
    }
    
}