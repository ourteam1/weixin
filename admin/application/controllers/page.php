<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Page extends MS_Controller
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 公司介绍
     */
    public function company()
    {
        $Company = $this->input->post('Company');
        if ($Company) {
            $upd_company_res = $this->load_model('pages_model')->update_company($Company);
            if (isset($upd_company_res['error'])) {
                $this->session->set_flashdata('error_message', $upd_company_res['error']);
                $this->session->set_flashdata('Company', $Company);
                redirect(current_url());
            }
            $this->session->set_flashdata('success_message', $upd_company_res['message']);
            redirect(current_url());
        }

        $company = $this->load_model('pages_model')->get_company();
        foreach ($company as $key => $value) {
            $this->view->assign($key, $value);
        }

        $this->view->display('page/company.html');
    }

}
