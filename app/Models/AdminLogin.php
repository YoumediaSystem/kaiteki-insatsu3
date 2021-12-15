<?php

namespace App\Models;


class AdminLogin {

    private $expire_days = 30;

	protected $admin = [
        'id' => 0
        ,'name' => ''
        ,'role' => ''
        ,'client_code' => ''
        ,'log' => ''
    ];

    function __construct(){
        helper('cookie');
    }

	function getAdminInfo() {

		return $this->admin;
	}

    function adjustParam($param) {

        return $param;
    }

    function checkParam($param) {

        $lib = new \App\Models\CommonLibrary();

        $error = [];
        if(empty($param['mail_address']))
            $error[] = 'IDを入力してください。';

        elseif (!$lib->is_mail($param['mail_address']))
            $error[] = 'IDを確認してください。';

        if(empty($param['pass']))
            $error[] = 'パスワードを入力してください。';

        return $error;
    }

    function is_login($param = null) {

        $b_auth = false;


        $Admin = new \App\Models\DB\Admin();
        $AdminAuth = new \App\Models\DB\AdminAuth();

        $token = get_cookie('admin_token');

        // トークン有効期限
        $DT = new \Datetime();
        $DT->setTimeZone( new \DateTimeZone('Asia/Tokyo'));
        $DT->modify('-'.$this->expire_days.' days');

        $expire_date = $DT->format('Y-m-d H:i:s');

        // セッション認証
        $session = session();
        $log = '';

        if ($session->has('admin_id')) {

            $b_auth = true;
            $admin = [
                'id'            => $session->get('admin_id'),
                'name'          => $session->get('admin_name'),
                'role'          => $session->get('admin_role'),
                'client_code'   => $session->get('admin_client_code')
            ];
        }

        // トークン認証
        if (!$b_auth && !empty($token)) {

            $data1 = $AdminAuth->find($token);

            if (isset($data1[0]['admin_id'])) {

                $data2 = $Admin->getFromID($data1[0]['admin_id']);

                if (isset($data2['id'])) {
                    $admin = $data2;
                    $b_auth = true;
                }

                // 認証OKならトークン・cookieを更新する
                if ($b_auth) {
                    $DT1 = new \Datetime($data1[0]['access_date']);
                    $DT1->setTimeZone( new \DateTimeZone('Asia/Tokyo'));

                    $DT2 = new \Datetime();
                    $DT2->setTimeZone( new \DateTimeZone('Asia/Tokyo'));

                    $passed_time = ((int)$DT2->format('U') - (int)$DT1->format('U'));

                    // 3600 = 1hour passed
                    if (3600 < $passed_time)
                        $this->setToken($admin['id']);
                }
            }
        }

        // POST認証
        if (!$b_auth && !empty($param['mail_address']) && !empty($param['pass'])) {

            $data = $Admin->getFromAuthInfo($param);

            $b_auth = (isset($data['name']));

            // 認証OKならトークン・cookieを設定する
            if ($b_auth) {
                $admin = $data;
                $this->setToken($data['id']);

            } else {
                $log .= $data;
            }
        }

        // 認証情報
        if ($b_auth) $this->admin = [
            'id'            => $admin['id']
            ,'name'         => $admin['name']
            ,'role'         => $admin['role']
            ,'client_code'  => $admin['client_code']
        ];

        if ($b_auth && !$session->has('admin_id')) {
            $session->set('admin_id',           $admin['id']);
            $session->set('admin_name',         $admin['name']);
            $session->set('admin_role',         $admin['role']);
            $session->set('admin_client_code',  $admin['client_code']);
        }

        if (!empty($log)) $session->set('log', $log);

        return $b_auth;
    }

    private function setToken($id) {

        $AdminAuth = new \App\Models\DB\AdminAuth();

        $b_not_conflict = true;

        while ($b_not_conflict) {

            $hash = bin2hex(openssl_random_pseudo_bytes(32));

//            $auth = $AdminAuth->where('token', $hash)->find();
            $auth = $AdminAuth->find($hash);

//            $log = json_encode($auth);

            if (empty($auth[0]['token'])) {

                $b_not_conflict = false;

                $AdminAuth->insert([
                    'admin_id'	=> $id,
                    'token'		=> $hash
                ]);
            }
        }

        // トークン有効期限
        $DT = new \Datetime();
        $DT->setTimeZone(new \DateTimeZone('Asia/Tokyo'));
        $DT->modify($this->expire_days.' days');

        set_cookie('admin_token', $hash, (int)$DT->format('U'));
    }

    function logout() {

        $session = session();
        $id = $session->get('admin_id');

        if (!empty($id)) {

            $AdminAuth = new \App\Models\DB\AdminAuth();

            $AdminAuth->where('admin_id', $id)->delete();

            setcookie('admin_token', '', 0);

            $this->admin = [
                'id'            => 0
                ,'name'         => ''
                ,'role'         => ''
                ,'client_code'  => ''
            ];

            $session->destroy();
        }
    }

}