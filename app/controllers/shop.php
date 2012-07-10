<?php
class Shop extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users');
    }
    public function index()
    {
        $this->load->model('ProjectManager');
        $this->load->model('Department');
        $data = array(
            'ParamList' => $this->input->get(),
            'Portfolio' => $this->Users->getPortfolioFacade(false,1,false,true),
            'queryString' => preg_replace ('(page=\d+)','',$_SERVER['QUERY_STRING']),
            'projectType' => $this->ProjectManager->getTypes(),
            'DepartmentNames' => $this->Department->getDepartmentsPlainArray()
        );
        $ConfigData = array(
                                "ContentTemplate" => "shop/index");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
}