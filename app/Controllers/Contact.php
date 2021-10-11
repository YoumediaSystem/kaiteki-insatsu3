<?php

namespace App\Controllers;

class Contact extends BaseController
{
    const ONEDAY = 86400;

    protected $param = [];

    public function __construct()
    {
        $request = \Config\Services::request();
        $a1 = $request->getGet();
        $a2 = $request->getPost();
        $a = array_merge($a1, $a2); 
        $a['param'] = $a;

        $Config = new \App\Models\Service\Config();
        $server = $request->getServer();

        $site_param = $Config->site_params();

        $a['site'] = $site_param;
        $a['environ'] = $Config->getEnviron();

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

        if ($this->check_login()) {

            $User = new \App\Models\DB\User();

            $id = (int)$this->param['user']['id'];
            $data = $User->getFromID($id);

            $this->param['real_name'] = $data['name'] ?? '';
            $this->param['real_name_kana'] = $data['name_kana'] ?? '';
            $this->param['mail_address'] = $data['mail_address'] ?? '';
        }

        $this->setCommonViews();
        return view('contact/index', $this->param);
    }

    public function check()
    {
        if ($this->check_mente()) return redirect()->to('/mente');

        $Config = new \App\Models\Service\Config();
        $Contact = new \App\Models\Mail\Contact();

        $this->param = $Contact->adjustContact($this->param);

        $error = $Contact->checkContact($this->param);

        if (count($error)) $this->param['error'] = $error;

        $this->param['preview'] = $Contact->getPreviewArray();
        $this->param['not_preview'] = $Contact->getNotPreviewArray();
        $this->param['key2name'] = $Contact->getKey2NameArray();

        $mail = $Config->getMailAddress();
        $this->param['admin_mail_address'] = $mail['admin_mail_address'];

        $this->setCommonViews();
        return view('contact/check', $this->param);
    }

    public function sendmail()
    {
        if ($this->check_mente()) return redirect()->to('/mente');
        
        $Config = new \App\Models\Service\Config();
        $Contact = new \App\Models\Mail\Contact();

        $result = $Contact->sendContact($this->param);

        if ($result['result'] != 'ok')
            $this->param['error'] = $result['error'];

        $this->param['key2name'] = $Contact->getKey2NameArray();

        return view('contact/sendmail', $result);
    }

    public function thanks()
    {
        $this->setCommonViews();
        return view('contact/thanks', $this->param);
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
