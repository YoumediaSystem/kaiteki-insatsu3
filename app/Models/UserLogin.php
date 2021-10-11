<?php

namespace App\Models;


class UserLogin {

    private $expire_days = 30;

	protected $user = [
        'id' => 0
        ,'name' => ''
        ,'log' => ''
    ];

    function __construct(){
        helper('cookie');
    }

	function getUserInfo() {

		return $this->user;
	}

    function adjustParam($param) {


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
        $log = '';


        $User = new \App\Models\DB\User();
        $UserAuth = new \App\Models\DB\UserAuth();
//        $Crypt = new \App\Models\Crypt();

        $token = get_cookie('user_token');

        // 決済戻りでトークン切れの場合は、再復活させる
        if (empty($token) && !empty($param['trading_id'])) {

            $log .= '*';
            $data = (new \App\Models\DB\Payment())->getFromID($param['trading_id']);

            if (!empty($data['user_id'])) {
                $log .= '*';
                $data2 = $UserAuth
                ->where('user_id', $data['user_id'])
                ->orderBy('access_date', 'DESC')
                ->find();

                if (!empty($data2[0]['token'])) {
                    $log .= '*';
                    $token = $data2[0]['token'];
                }
            }
        }

        // トークン有効期限
        $DT = new \Datetime();
        $DT->setTimeZone( new \DateTimeZone('Asia/Tokyo'));
        $DT->modify('-'.$this->expire_days.' days');

        $expire_date = $DT->format('Y-m-d H:i:s');

        // セッション認証
        $session = session();

        if ($session->has('user_id')) {

            $b_auth = true;
            $user = [
                'id'    => $session->get('user_id'),
                'name'  => $session->get('user_name'),
                'point' => $session->get('user_point')
            ];
        }

        // トークン認証
        if (!$b_auth && !empty($token)) {

            $log .= '+';
            $data1 = $UserAuth
                ->where('token', $token)
                ->find();

            if (!empty($data1[0]['user_id'])) {

                $log .= '+';
                $data2 = $User->getFromID($data1[0]['user_id']);

                if (!empty($data2['id'])) {
                    $log .= '+';
                    $user = $data2;
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
                        $this->setToken($user['id']);
                }
            }
        }

        // POST認証
        if (!$b_auth && !empty($param['mail_address']) && !empty($param['pass'])) {

            $data = $User->getFromAuthInfo($param);

            $b_auth = (isset($data['name']));

            // 認証OKならトークン・cookieを設定する
            if ($b_auth) {
                $user = $data;
                $this->setToken($data['id']);

            } else {
                $log .= $data;
            }
        }

        // 認証情報
        if ($b_auth) $this->user = [
            'id'        => $user['id']
            ,'name'     => $user['name']
            ,'point'    => $user['point']
        ];

        if ($b_auth && !$session->has('user_id')) {
            $session->set('user_id',    $user['id']);
            $session->set('user_name',  $user['name']);
            $session->set('user_point', $user['point']);
        }

        if (!empty($log)) $session->set('log', $log);

        return $b_auth;
    }

    private function setToken($id) {

        $UserAuth = new \App\Models\DB\UserAuth();

        $not_clear_conflict = true;

        while ($not_clear_conflict) {

            $hash = bin2hex(openssl_random_pseudo_bytes(32));

            $auth = $UserAuth->where('token', $hash)->find();

            if (empty($auth[0]['token'])) {

                $not_clear_conflict = false;

                $UserAuth->insert([
                    'user_id'	=> $id,
                    'token'		=> $hash
                ]);
            }
        }

        // トークン有効期限
        $DT = new \Datetime();
        $DT->setTimeZone(new \DateTimeZone('Asia/Tokyo'));
        $DT->modify($this->expire_days.' days');

        set_cookie('user_token', $hash, (int)$DT->format('U'));
    }

    function logout() {

        $session = session();
        $id = $session->get('user_id');

        if (!empty($id)) {

            $UserAuth = new \App\Models\DB\UserAuth();

            $UserAuth->where('user_id', $id)->delete();

            setcookie('user_token', '', 0);

            $this->user = [
                'id'        => 0
                ,'name'     => ''
                ,'point'    => 0
            ];

            $session->destroy();
        }
    }

}