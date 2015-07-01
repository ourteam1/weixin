<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site extends MS_Controller {

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 系统登录页面
     */
    public function index() {
        $this->view->set_layout('layout/layout.html')->display('site/index.html');
    }

    /**
     * 登录
     */
    public function login() {
        $login = $this->input->post('Login');

        if ($login) {
            $res = $this->load_model('admin_model')->login($login);
            if (!isset($res['error'])) {
                redirect(site_url('site/index'));
            }

            $login['error'] = $res['error'];
            $this->session->set_flashdata('login', $login);
            redirect(site_url('site/login'));
        }

        if ($this->session->flashdata('login')) {
            $this->view->assign($this->session->flashdata('login'));
        }

        $this->view->set_layout('layout/login-layout.html')->display('site/login.html');
    }

    /**
     * 退出
     */
    public function logout() {
        $this->load_model('admin_model')->logout();
        redirect(site_url('site/login'));
    }

}
