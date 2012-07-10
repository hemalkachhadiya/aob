<?php
class News extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SimpleDataManager');
    }
    public function index()
    {
        $this->setFormTemplator('createNewsItem');
        $data = array(
            "NewsList"           => $this->SimpleDataManager->getList(),
            "NewsListAmount"     => $this->SimpleDataManager->getListAmount(),
        );
        $ConfigData = array("ContentTemplate" => "news/index");
        $this->load->helper('string_truncate');
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    public function item($id)
    {
        $this->setFormTemplator('createNewsItem');
        $data = array(
            'newsItem'   => $this->SimpleDataManager->getItem($id)
        );
        $ConfigData = array("ContentTemplate" => "news/item");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }

    public function delete($projectId){
        $this->SimpleDataManager->delete($projectId);
        redirect('news');
    }

    protected function setFormTemplator($validationScheme){
        if ($this->form_validation->run($validationScheme))
        {
            $Method = $validationScheme;
            $this->SimpleDataManager->$Method();
        }
        return false;
    }
}