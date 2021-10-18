<?php

namespace App\Controllers;

class User extends BaseController
{
    const ONEDAY = 86400;

    protected $param = [];

    public function __construct()
    {
        $request = \Config\Services::request();
        $a1 = $request->getGet();
        $a2 = $request->getPost();
        $a = array_merge($a1, $a2); 

        $Config = new \App\Models\Service\Config();
        $server = $request->getServer();

        $site_param = $Config->site_params();

        $a['site'] = $site_param;
        $a['environ'] = $Config->getEnviron();
        $a['b_mente'] = $Config->isMenteUser();

        $session = session();

        $a['user'] = [
            'id' => $session->get('user_id'),
            'name' => $session->get('user_name'),
            'point' => $session->get('user_point')
        ];

        $this->param = $a;
    }

    private function setCommonViews() {

        $this->param['view'] = [
            'header'    => view('user/_header', $this->param),
            'nav'       => view('user/_nav',    $this->param),
            'side'      => view('user/_side',   $this->param),
            'footer'    => view('user/_footer', $this->param)
        ];
    }

    public function index()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();

        $Config = new \App\Models\Service\Config();

        $view_file = ($Config->isRealOpen())
        ? 'user/index' : 'user/index_soon';

        return view($view_file, $this->param);
    }

    public function index_test()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/index', $this->param);
    }

    public function index_ph2()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/index_ph2', $this->param);
    }

    public function guide()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/guide', $this->param);
    }

    public function limits()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/limits', $this->param);
    }

    public function outline_offset()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        if (empty($this->param['id']))
            $this->param['id'] = 1;

        // 商品概要データ読込
        $ProductSet = new \App\Models\DB\ProductSet();
        $this->param += $ProductSet->getFromID($this->param['id']);

        // 冊数P数マトリクスデータ読込
        $MatrixData = new \App\Models\Service\MatrixData();
        $param = [
            'client_code' => 'taiyou',
            'product_code' => 'offset'
        ];

        $this->param['matrix'] = $MatrixData->getMatrixData($param);

        $param['paper_size'] = 'b5';
        $this->param['matrix_b5'] = $MatrixData->getMatrixData($param);

        $this->setCommonViews();
        return view('user/outline_offset', $this->param);
    }

    public function outline_ondemand()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        if (empty($this->param['id']))
            $this->param['id'] = 2;

        // 商品概要データ読込
        $ProductSet = new \App\Models\DB\ProductSet();
        $this->param += $ProductSet->getFromID($this->param['id']);

        // 冊数P数マトリクスデータ読込
        $MatrixData = new \App\Models\Service\MatrixData();
        $param = [
            'client_code' => 'taiyou',
            'product_code' => 'ondemand'
        ];

        $this->param['matrix'] = $MatrixData->getMatrixData($param);

        $param['paper_size'] = 'b5';
        $this->param['matrix_b5'] = $MatrixData->getMatrixData($param);

        $this->setCommonViews();
        return view('user/outline_ondemand', $this->param);
    }

    public function company()
    {
        $this->setCommonViews();
        return view('user/company', $this->param);
    }

    public function agree()
    {
        $this->setCommonViews();
        return view('user/agree', $this->param);
    }

    public function privacy()
    {
        $this->setCommonViews();
        return view('user/privacy', $this->param);
    }

    public function faq()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/faq', $this->param);
    }


    public function signup_auth()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/signup/auth', $this->param);
    }

    public function signup_mailauth()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();

        // invalid mail address
        $lib = new \App\Models\CommonLibrary();
        $AuthCommon = new \App\Models\DB\AuthCommon();

        if (!$lib->is_mail($this->param['mail'])) {
            $this->param['error'] = ['メールアドレスを確認してください'];
            return view('user/signup/auth', $this->param);
        }

        // already signup
        $User = new \App\Models\DB\User();
        $status = $User->getStatusFromMail($this->param['mail']);

        if ($status !== false) {
            $this->param['error'] = [$User->getError_signup($status)];
            return view('user/signup/auth', $this->param);
        }

        // make auth data
        $this->param['type'] = 'signup';
        $this->param['ex'] = [
            'mail' => (string)$this->param['mail']
        ];
        $this->param['hash'] = $AuthCommon->makeHash($this->param);
        unset($this->param['ex']);

        if ($this->param['hash'] === false) {
            $this->param['error'] = ['ただいま大変込み合っております。時間をおいて再度お試しください。'];
            return view('user/signup/auth', $this->param);
        }

        // send mail
        $this->param['action'] = '/signup_agree';
        $this->param['auth_url'] = $AuthCommon->makeURL($this->param);

        $this->param['mail_address'] = (string)$this->param['mail'];

        if (!(new \App\Models\Mail\Auth())->sendAuth($this->param)) {
            $this->param['error'] = ['メール送信できませんでした。時間をおいて再度お試しください。'];
            return view('user/signup/auth', $this->param);
        }

        return view('user/signup/auth_send', $this->param);
    }

    public function signup_send()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/signup/send', $this->param);
    }

    public function signup_agree()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();

        $AuthCommon = new \App\Models\DB\AuthCommon();

        if (!$AuthCommon->is_auth($this->param)) {
            $this->param['error'] = ['新規会員登録メール認証できませんでした。'];
            return view('user/signup/auth', $this->param);
        }
        return view('user/signup/agree', $this->param);
    }

    public function signup_form()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();

        $AuthCommon = new \App\Models\DB\AuthCommon();

        if (!$AuthCommon->is_auth($this->param)) {
            $this->param['error'] = ['認証データがありません。'];
            return view('user/signup/auth', $this->param);
        }

        $a = $AuthCommon->getBufferData($this->param);
        
        if (!empty($a['mail']))
            $this->param['mail_address'] = $a['mail'];

        return view('user/signup/form', $this->param);
    }

    public function signup_confirm()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();

        $AuthCommon = new \App\Models\DB\AuthCommon();

        if (!$AuthCommon->is_auth($this->param)) {
            $this->param['error'] = ['認証データがありません。'];
            return view('user/signup/auth', $this->param);
        }

        $a = $AuthCommon->getBufferData($this->param);
        
        if (!empty($a['mail']))
            $this->param['mail_address'] = $a['mail'];
        
        unset($AuthCommon);
        
        $User = new \App\Models\DB\User();
        
        $this->param = $User->adjustParam($this->param);
        $error = $User->checkParam($this->param);

        if (count($error)) {
            $this->param['error'] = $error;
            return view('user/signup/form', $this->param);
        }

        return view('user/signup/confirm', $this->param);
    }

    public function signup_do()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $AuthCommon = new \App\Models\DB\AuthCommon();

        if (!$AuthCommon->is_auth($this->param)) {
            $this->param['error'] = ['認証データがありません。'];
            return view('user/signup/auth', $this->param);
        }

        $a = $AuthCommon->getBufferData($this->param);
        
        if (!empty($a['mail']))
            $this->param['mail_address'] = $a['mail'];
        
        unset($AuthCommon);
        
        $User = new \App\Models\DB\User();
        
        $this->param = $User->adjustParam($this->param);
        $error = $User->checkParam($this->param);

        $user_id = 0;
        if (!count($error)) {
            $param = $User->makeData($this->param);
            $user_id = $User->modify($param);
        }

        if (empty($user_id))
            $this->param['error'] = ['新規登録できませんでした。時間をおいて再度お手続きするか、お問合せください。'];

        if (count($error)) $this->param['error'] = $error;

        else {
            $Config = new \App\Models\Service\Config();

            $point = $Config->getSignupPoint();

            if ($point) {

                $p_param = [
                    'point' => $point,
                    'detail' => '新規登録ポイント',
                    'user_id' => $user_id,
                    'expire_date' => $Config->getPointExpireDatetext()
                ];
    
                // 新規登録ポイント付与
                $Point = new \App\Models\DB\Point();
                $Point->modify($p_param);
                $Point->modUserPoint($user_id);
            }

            // ログイン状態にする
            (new \App\Models\UserLogin())->is_login([
                'mail_address' => $this->param['mail_address'],
                'pass' => $this->param['pass']
            ]);
        }

        return view('user/signup/do', $this->param);
    }


    public function signup_complete()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/signup/complete', $this->param);
    }


    public function login()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        $this->setCommonViews();
        return view('user/login', $this->param);
    }

    public function login_do()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        $UserLogin = new \App\Models\UserLogin();

        $error = $UserLogin->checkParam($this->param);

        if (count($error) == 0) {
            $b = $UserLogin->is_login($this->param);
            if (!$b) $error[] = 'ID・パスワードを確認してください。';
        }

        if (count($error)) {
            $this->param['error'] = $error;

            $this->setCommonViews();
            return view('user/login', $this->param);
        }

        return view('user/login_do', $this->param);
    }

    public function mypage()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $id = (int)$this->param['user']['id'];

        $data = (new \App\Models\DB\User())->getFromID($id);
        $this->param += $data;

        $this->param['b_yet_payment'] =
            (new \App\Models\DB\OrderHistory())->getYetPayment($id);
        
        $Payment = new \App\Models\DB\Payment();
        $this->param['b_aborted_payment'] = $Payment->isAbortedPayment($id);
        $this->param['b_waiting_netbank'] = $Payment->isWaitingBankPayment($id);

        (new \App\Models\DB\Point())->modUserPoint($id);
        $session = session();
        $this->param['user']['point'] = $session->get('user_point');

        $this->setCommonViews();
        return view('user/member/mypage', $this->param);
    }

    public function logout() {
        $UserLogin = new \App\Models\UserLogin();
        $UserLogin->logout();
        $this->param['user'] = $UserLogin->getUserInfo();

        $this->setCommonViews();
        return view('user/logout', $this->param);
    }


    public function forget_pass()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/forget/pass', $this->param);
    }

    public function forget_pass_mailauth()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        $session = session();

        // invalid mail address
        $lib = new \App\Models\CommonLibrary();
        $AuthCommon = new \App\Models\DB\AuthCommon();

        if (!$lib->is_mail($this->param['mail_address'])) {
            $this->param['error'] = ['メールアドレスを確認してください'];
            return view('user/forget/pass', $this->param);
        }

        // active account exists
        $User = new \App\Models\DB\User();
        $id = $User->getIDfromMail($this->param['mail_address']);
        $status = $User->getStatusFromMail($this->param['mail_address']);

        $session->set('log','ID:'.$id.', status:'.$status);

        if (empty($id) || $status < 0) {
            $this->param['error'] = ['パスワード変更できません。'];
            return view('user/forget/pass', $this->param);
        }

        // make auth data
        $this->param['type'] = 'forget_pass';
        $this->param['ex'] = [
            'mail_address' => $this->param['mail_address']
        ];
        $this->param['hash'] = $AuthCommon->makeHash($this->param);
        unset($this->param['ex']);

        if ($this->param['hash'] === false) {
            $this->param['error'] = ['ただいま大変込み合っております。時間をおいて再度お試しください。'];
            return view('user/signup/pass', $this->param);
        }

        // send mail
        $this->param['action'] = '/forget_pass_form';
        $this->param['auth_url'] = $AuthCommon->makeURL($this->param);

        if (!(new \App\Models\Mail\Auth())->send($this->param)) {
            $this->param['error'] = ['メール送信できませんでした。時間をおいて再度お試しください。'];
            return view('user/forget/pass', $this->param);
        }

        return view('user/forget/pass_mailauth', $this->param);
    }

    public function forget_pass_send()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $AuthCommon = new \App\Models\DB\AuthCommon();
        $this->param['action'] = '/forget_pass_form';
        $this->param['auth_url'] = $AuthCommon->makeURL($this->param);

        $this->setCommonViews();
        return view('user/forget/pass_send', $this->param);
    }

    public function forget_pass_form()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();

        $AuthCommon = new \App\Models\DB\AuthCommon();

        if (!$AuthCommon->is_auth($this->param)) {
            $this->param['error'] = ['認証データがありません。'];
            return view('user/error', $this->param);
        }

        $data = $AuthCommon->getBufferData($this->param);
        $this->param['mail_address'] = $data['mail_address'];

        return view('user/forget/pass_form', $this->param);
    }

    public function forget_pass_do()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();

        $AuthCommon = new \App\Models\DB\AuthCommon();

        if (!$AuthCommon->is_auth($this->param)) {
            $this->param['error'] = ['認証データがありません。'];
            return view('user/error', $this->param);
        }

        $data = $AuthCommon->getBufferData($this->param);
        $this->param['mail_address'] = $data['mail_address'];

        $User = new \App\Models\DB\User();
        $param = $this->param;
        $error = $User->checkPass($param);

        $b = false;
        if (!count($error)) {
            $param['id'] = $User->getIDfromMail($data['mail_address']);

            $b = $User->modifyPass($param);
            if (!$b) $this->param['error'] =
                ['パスワード変更できませんでした。時間をおいて再度お手続きするか、お問合せください。'];

            else $AuthCommon->delete($this->param['hash']);
        }

        if (count($error)) $this->param['error'] = $error;

        return view('user/forget/pass_do', $this->param);
    }

    public function forget_pass_complete()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/forget/pass_complete', $this->param);
    }


    public function forget_mail()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/forget/mail', $this->param);
    }

    public function forget_mail_mailsend()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();

        $User = new \App\Models\DB\User();
        $this->param = $User->adjustParam($this->param);
        $error = $User->checkParam($this->param);

        $lib = new \App\Models\CommonLibrary();
        if (!empty($this->param['mail_address'])
        &&  !$lib->is_mail($this->param['mail_address']))
            $error[] = 'メールアドレスを確認してください。';

        if (!count($error)) {
            if (!$User->is_userFromProfile($this->param))
                $error[] = '会員登録がありません。氏名カナ・電話番号・生年月日をご確認ください。';
        }

            if (count($error)) {
            $this->param['error'] = $error;
            return view('user/forget/mail', $this->param);
        }

        if (!(new \App\Models\Mail\ForgetMail())->sendForgetMail($this->param)) {
            $this->param['error'] = ['メール送信できませんでした。時間をおいて再度お試しください。'];
            return view('user/forget/pass', $this->param);
        }

        return view('user/forget/mail_mailsend', $this->param);
    }

    public function forget_mail_send()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/forget/mail_send', $this->param);
    }

    public function forget_mail_do()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/forget/mail_do', $this->param);
    }

    public function forget_mail_complete()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $this->setCommonViews();
        return view('user/forget/mail_complete', $this->param);
    }

    public function point_history()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $data = (new \App\Models\DB\Point())->getList4user($this->param['user']['id']);

        $this->param['history'] = $data;

        $this->setCommonViews();
        return view('user/member/point_history', $this->param);
    }

    public function user()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $this->setCommonViews();
        return view('user/member/user', $this->param);
    }

    public function user_form()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $User = new \App\Models\DB\User();
        $data = $User->getFromID($this->param['user']['id']);
        $this->param = array_merge($this->param, $data);

        $this->setCommonViews();
        return view('user/member/user_form', $this->param);
    }

    public function user_confirm()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');
        
        $User = new \App\Models\DB\User();
        $this->param = $User->adjustParam($this->param);
        $error = $User->checkParam($this->param);

        $this->setCommonViews();

        if (count($error)) {
            $this->param['error'] = $error;
            return view('user/member/user_form', $this->param);
        }

        return view('user/member/user_confirm', $this->param);
    }

    public function user_do()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $User = new \App\Models\DB\User();
        $param = $User->adjustParam($this->param);
        $error = $User->checkParam($param);
        $param['id'] = (int)$this->param['user']['id'];

        if (!count($error)) $b = $User->modify($param);

        if (!$b) $this->param['error'] = ['会員情報変更できませんでした。時間をおいて再度お手続きするか、お問合せください。'];

        if (count($error)) $this->param['error'] = $error;

        return view('user/member/user_do', $this->param);
    }


    public function mail_form()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $User = new \App\Models\DB\User();
        $data = $User->getFromID($this->param['user']['id']);
        $data['old_mail_address'] = $data['mail_address'];
        $this->param = array_merge($this->param, $data);

        $this->setCommonViews();
        return view('user/member/mail_form', $this->param);
    }

    public function mail_auth_send()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $this->setCommonViews();
        
        $lib = new \App\Models\CommonLibrary();
        $AuthCommon = new \App\Models\DB\AuthCommon();

        if (!$lib->is_mail($this->param['new_mail_address'])) {
            $this->param['error'] = ['メールアドレスを確認してください'];
            return view('user/member/mail_form', $this->param);
        }

        // make auth data
        $this->param['type'] = 'mail';
        $this->param['ex'] = [
            'old_mail_address' => (string)$this->param['old_mail_address'],
            'new_mail_address' => (string)$this->param['new_mail_address']
        ];
        $this->param['hash'] = $AuthCommon->makeHash($this->param);
        unset($this->param['ex']);

        if ($this->param['hash'] === false) {
            $this->param['error'] = ['ただいま大変込み合っております。時間をおいて再度お試しください。'];
            return view('user/member/mail_form', $this->param);
        }

        // send mail
        $this->param['action'] = '/user/mail_do';
        $this->param['auth_url'] = $AuthCommon->makeURL($this->param);
        $this->param['mail_address'] = (string)$this->param['new_mail_address'];

        if (!(new \App\Models\Mail\Auth())->sendAuth($this->param)) {
            $this->param['error'] = ['メール送信できませんでした。時間をおいて再度お試しください。'];
            return view('user/member/mail_form', $this->param);
        }

        return view('user/member/mail_auth_send', $this->param);
    }

    public function mail_do()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        $AuthCommon = new \App\Models\DB\AuthCommon();

        if (!$AuthCommon->is_auth($this->param)) {
            $this->param['error'] = ['認証データがありません。'];
            return view('user/error', $this->param);
        }

        $a = $AuthCommon->getBufferData($this->param);
        $AuthCommon->clearBuffer($this->param);
        unset($AuthCommon);
        
        
        $User = new \App\Models\DB\User();
        
        $id = $User->getIDfromMail($a['old_mail_address']);

        if (empty($id)) {
            $this->param['error'] = ['会員登録がありません。'];
            return view('user/error', $this->param);
        }

        $User->modifyMail([
            'id' => $id,
            'mail_address' => $a['new_mail_address']
        ]);

        return view('user/member/mail_do', $this->param);
    }

    public function mail_changed()
    {
        return view('user/member/mail_changed', $this->param);
    }

/*
    public function pass_auth()
    {
        if (!$this->check_login()) return redirect()->to('/login');
        
        $this->setCommonViews();
        return view('user/member/pass_auth', $this->param);
    }
*/
    public function pass_form()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');
        
        $this->setCommonViews();
        return view('user/member/pass_form', $this->param);
    }

    public function pass_do()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');

        $User = new \App\Models\DB\User();
        $param = $this->param;
        $error = $User->checkPass($param);
        $param['id'] = (int)$this->param['user']['id'];

        $b = false;
        if (!count($error)) {
            $b = $User->modifyPass($param);
            if (!$b) $this->param['error'] =
                ['パスワード変更できませんでした。時間をおいて再度お手続きするか、お問合せください。'];
        }

        if (count($error)) $this->param['error'] = $error;

        return view('user/member/pass_do', $this->param);
    }


    public function resign_notice()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');
        
        $this->param['b_doing_order'] =
            (new \App\Models\DB\OrderHistory())
                ->isDoing($this->param['user']['id']);

        $this->setCommonViews();
        return view('user/resign/notice', $this->param);
    }

    public function resign_form()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');
        
        $this->setCommonViews();
        return view('user/resign/form', $this->param);
    }

    public function resign_confirm()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');
        
        $this->setCommonViews();
        return view('user/resign/confirm', $this->param);
    }

    public function resign_do()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        if (!$this->check_login()) return redirect()->to('/login');
        
        $User = new \App\Models\DB\User();
        
        $param = [];
        $param['id'] = (int)$this->param['user']['id'];
        $data = $User->getFromID($param['id']);

        $param['status'] = -1; // delete
        $User->modifyStatus($param);

        //$session = session();
        //$session->set('log', json_encode($data));

        $this->param['mail_address'] = $data['mail_address'];
        unset($User);

        $Mail = new \App\Models\Mail\Resign();
        $Mail->send($this->param);

        return view('user/resign/do', $this->param);
    }

    public function resign_complete()
    {
        $UserLogin = new \App\Models\UserLogin();
        $UserLogin->logout();
        $this->param['user'] = $UserLogin->getUserInfo();

        $this->setCommonViews();
        return view('user/resign/complete', $this->param);
    }

    public function mente()
    {
        $this->setCommonViews();
        return view('user/mente', $this->param);
    }

    // ログインチェック

    private function check_login() {
        $UserLogin = new \App\Models\UserLogin();
        if (!$UserLogin->is_login($this->param)) return false;

        $this->param['user'] = $UserLogin->getUserInfo();
        return true;
    }

    private function check_mente() {
        $Config = new \App\Models\Service\Config();
        return $Config->isMenteUser();
    }
}
