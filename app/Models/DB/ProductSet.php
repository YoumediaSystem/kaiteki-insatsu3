<?php

namespace App\Models\DB;

use CodeIgniter\Model;
//use CodeIgniter\Database\ConnectionInterface;

class ProductSet extends Model
{
    protected $statusName = [
        1 => '有効'
      ,-1 => '削除'
    ];

    protected $table      = 'product_set';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'name',
        'name_en',
        'max_order',
        'ordered',
        'open_date',
        'close_date',
        'status',
        'note',
        'admin_id',
        'ex'
    ];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;    

    protected $protectedFields = [
    ];

    protected $key2name = [
    ];

    protected $necessary = [
    ];

    protected $deny_url_key = [
    ];

    protected $max_input_length = [
    ];

    protected $rows_of_page = 10;

    private $salt = 'youclub1211';

    function getStatusNameArray() {
        return (array)$this->statusName;
    }

    public function getFromID($id) {

        if (empty($id)) return [];

        $data = $this->find($id);

        if (empty($data['id'])) return [];

        return $this->parseData($data);
/*
        if (empty($data[0]['id'])) return [];
        return $data[0];
*/
    }

    public function getList($param) {

        $b_all = (
            empty($param['client_code'])
        &&  empty($param['name'])
        );
        $page = $param['page'] ?? 0;

        if ($b_all)
            $count = $this->countAllResults(false);

        else {
            $count = $this
            ->like('name', $param['name'] ?? '', 'both')
            ->where('client_code', $param['client_code'] ?? '')
            ->countAllResults(false);
        }
        $data = $this
        ->findAll($this->rows_of_page, $this->rows_of_page * $page);

        $result = [];
        foreach($data as $key=>$val)
            $result[] = $this->parseData($val);

        $result['pager'] = (new \App\Models\CommonLibrary())
            ->getPagerInfo([
                'page' => $page,
                'count_all' => $count,
                'rows_of_page' => (int)$this->rows_of_page
            ]);

        return $result;
    }


    // 値補正
    function adjustParam($param)
    {
        $key = 'open_date';
        if (isset($param[$key.'_y'])
        &&  isset($param[$key.'_m'])
        &&  isset($param[$key.'_d'])
        ) { $param[$key]  = $param[$key.'_y'].'-';
            $param[$key] .= $param[$key.'_m'].'-';
            $param[$key] .= $param[$key.'_d'];
        }
        $key = 'close_date';
        if (isset($param[$key.'_y'])
        &&  isset($param[$key.'_m'])
        &&  isset($param[$key.'_d'])
        ) { $param[$key]  = $param[$key.'_y'].'-';
            $param[$key] .= $param[$key.'_m'].'-';
            $param[$key] .= $param[$key.'_d'];
        }

        return $param;
    }

    // 値チェック
    function checkParam($param)
    {
        $lib = new \App\Models\CommonLibrary();
        $error = [];
/*
        $param['mode'] = $param['mode'] ?? '';

        $necessary = (array)$this->necessary;
        if ($param['mode'] == 'modify') {

            $key = array_search('pass', $necessary);
            if ($key !== false) unset($necessary[$key]);
        }

        if ($param['mode'] == 'forget_mail') {
            $necessary = (array)$this->necessary_forget_mail;
        }

        foreach($necessary as $key) { // 必須入力チェック
            if(empty($param[$key]))
            $error[] = $this->key2name[$key].'を入力してください。';
        }
        
        foreach($this->max_input_length as $key=>$val) { // 文字数上限チェック
            if(!empty($param[$key]) && $val < mb_strlen($param[$key],'UTF-8'))
            $error[] = $this->key2name[$key].'は'.$val.'文字以内で入力してください。';
        }
        
        foreach($this->max_input_kana_length as $key=>$val) { // 氏名カナチェック

            if(!empty($param[$key])) {

                $text = mb_convert_kana($param[$key],'k','UTF-8');

                if(!empty($text) && $val < mb_strlen($text,'UTF-8'))
                    $error[] = $this->key2name[$key].'は'.$val.'文字以内で入力してください（濁点を1文字として）';
            }
        }

        foreach($this->deny_url_key as $key) { // URL混入チェック
            if(!empty($param[$key]) && $lib->is_url($param[$key]))
            $error[] = $this->key2name[$key].'に送信できない文字が含まれています。';
        }

        foreach($this->name_kanji_key as $key) { // 氏名漢字チェック

            if(!empty($param[$key])) {

                $bad_character = $lib->findOutOfJIS1or2($param[$key]);

                if(!empty($bad_character))
                $error[] = $this->key2name[$key].'に登録できない文字（'
                    .$bad_character.'）が含まれています。常用漢字またはカナ文字で登録してください。';
            }
        }

        if (isset($param['pass'])
        &&  !preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,16}+\z/i', $param['pass']))
            $error[] = 'パスワードは半角英字と半角数字を含む8～16文字で入力してください。';
*/
        return $error;
    }

    function modify($param)
    {
        $data = [];
        $id = 0;

        $param = $this->makeData($param);

        if (!empty($param['id'])) {
            $data['id'] = $param['id'];
            
            $compare = $this->find($param['id']);

            foreach($this->allowedFields as $key)
                if (isset($param[$key])
                &&  $param[$key] != $compare[$key])
                    $data[$key] = $param[$key];

            $this->save($data);
            $id = $param['id'];

        } else {

            foreach($this->allowedFields as $key)
                if (!empty($param[$key]))
                    $data[$key] = $param[$key];
            
            $this->insert($data);
            $id = (int)$this->insertID;
        }

        return $id;
    }

    public function updateOrderedCount($id) {

        if (empty($id)) return false;

        $OrderHistory = new \App\Models\DB\OrderHistory();

        // 入金済以降をカウント
        $count = $OrderHistory
        ->where('product_set_id', $id)
        ->whereIn('status', [60,160,50,150,40,140])
        ->countAllResults();

        if (is_numeric($count)) {
            $this->save([
                'id' => $id,
                'ordered' => $count
            ]);
        }
        return true;
    }

    public function getSelector($matrix = null) { // 仮設定　どこに情報格納し編集するかは今後策定

        if (empty($matrix)) {
            $matrix = [
                'index_h' => ['10冊','20冊','30冊'],
                'index_v' => ['8p','12p','16p'],
            ];
        } // debug

        $select['print_number_all'] = [];

        foreach ($matrix['index_h'] as $key=>$val)
            $select['print_number_all'][] = $val;
        /*
        = [
            30,50,80,100,150];
            
        for ($i=200; $i<=$max_print_number; $i+=100) $select['print_number_all'][] = $i;
        */
        
        
        $select['print_page'] = [];
        
        foreach ($matrix['index_v'] as $key=>$val)
            $select['print_page'][] = $val;
        /*
        for ($i=$min_print_page;
            $i<=$max_print_page; $i+=4) $select['print_page'][] = $i;
        */
        
        $select['print_size'] = [
            'A6','B6','A5','B5'];
        
        $select['sex_type'] = [
            '男','女','未回答'];
        
        $select['print_direction'] = [
            '右綴じ','左綴じ'];
        
        $select['cover_paper'] = [
            'アートポスト180kg'];
        
        $select['cover_color'] = [
            'オフセット印刷フルカラー（表2・3印刷無し）'];
        
        $select['cover_process'] = [
            'クリアPP','マットPP'];
        
        $select['main_paper'] = [
             '上質70kg'
            ,'上質90kg'
            ,'スーパーバルギー'
            ,'書籍バルギー'
            //,'書籍バルギー'
            //,'上質110kg','スーパーバルギー'
        ];
        
        $select['main_color'] = [
            'オフセット印刷スミ1色'];
        
        $select['main_print_type'] = [
            'AM120線','FM'];
        
        $select['main_buffer_paper'] = [
            'なし'];
        //	'なし','前のみ','前後'];
        
        $select['main_buffer_paper_detail'] = [
        /*	
             '色上質・やまぶき'
            ,'色上質・レモン'
            ,'色上質・濃クリーム'
            ,'色上質・白茶'
            ,'色上質・オレンジ'
        
            ,'色上質・サーモン'
            ,'色上質・空'
            ,'色上質・ブルー'
            ,'色上質・水'
            ,'色上質・あじさい'
        
            ,'色上質・りんどう'
            ,'色上質・銀ねず'
            ,'色上質・コスモス'
            ,'色上質・さくら'
            ,'色上質・桃'
        
            ,'色上質・若草'
            ,'色上質・もえぎ'
            ,'色上質・若竹'
            ,'色上質・赤'
            ,'色上質・黒'
        
            ,'クラシコトレーシング・ピンク'
            ,'クラシコトレーシング・イエロー'
            ,'クラシコトレーシング・ブルー'
            ,'クラシコトレーシング・グレー'
            ,'クラシコトレーシング・オレンジ'
        
            ,'クラシコトレーシング・グリーン'
            ,'クラシコトレーシング・バイオレット'
        */
        ];
        
        $select['binding'] = [
            '無線綴じ'];
        
        $select['r18'] = [
            '全年齢向け','成人向け表現あり'];
        
        $select['delivery_buffer'] = [
            '自宅','直接搬入1','直接搬入2','快適本屋さん','その他'];
        
        $select['_type'] = [
            '自宅','直接搬入','快適本屋さんOnline','その他'];

        return $select;
    }

    function makeData($param) {

        if (!empty($param['ex']) && is_array($param['ex']))
            $param['ex'] = json_encode($param['ex']);
        
        return $param;
    }

    function parseData($param) {

        if (!empty($param['ex'])
        && !in_array($param['ex'], ['{}','NULL','null'])) {
            $a = json_decode($param['ex'], true);
            $param = array_merge($param, $a);
            unset($a, $param['ex']);
        }

        $param['client_name'] = 
        (new \App\Models\DB\Client())->getName($param['client_code']);

        $param['status_name'] = $this->statusName[$param['status']];
        
        $key = 'open_date';
        $a = explode('-', $param[$key]);
        $param[$key.'_y'] = $a[0];
        $param[$key.'_m'] = $a[1];
        $param[$key.'_d'] = $a[2];

        $key = 'close_date';
        $a = explode('-', $param[$key]);
        $param[$key.'_y'] = $a[0];
        $param[$key.'_m'] = $a[1];
        $param[$key.'_d'] = $a[2];

        return $param;
    }

}