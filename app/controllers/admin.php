<?php
class Admin extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Users');
        $this->authmanager->isAdminAccessed();
    }
    public function index(){
        //$data = array_merge($data,$this->TemplateData);
        $data = array(
            'ActiveDirectory'   => ''
        );
        $ConfigData = array("ContentTemplate" => "admin/index");
        $this->layoutmanager->getAdminTemplate($ConfigData,$data);
    }
    public function departments(){
        $this->load->model('Department');
        $data = array(
            'DepartmentList'    => $this->Department->getAdminDepartment(),
            'ActiveDirectory'   => 'Departments'
        );
        $ConfigData = array("ContentTemplate" => "admin/departments");
        $this->layoutmanager->getAdminTemplate($ConfigData,$data);
    }
    public function contactus(){
        $this->load->model('StaticPage');
        $data = array(
            'list'    => $this->StaticPage->getContactUs(true),
            'amount'  => $this->StaticPage->getContactUs(false),
            'ActiveDirectory'   => 'ContactUs'
        );
        $ConfigData = array("ContentTemplate" => "admin/contact_us");
        $this->layoutmanager->getAdminTemplate($ConfigData,$data);
    }
    public function questions(){
        $this->load->model('SimpleDataManager');
        $page = $this->input->get('page');
        if (!$page){
            $page = 1;
        }
        $data = array(
            'list'    => $this->SimpleDataManager->getQuestionList($page,10),
            'amount'  => $this->SimpleDataManager->getQuestionList(false,false),
            'ActiveDirectory'   => 'Questions'
        );
        $ConfigData = array("ContentTemplate" => "admin/questions");
        $this->layoutmanager->getAdminTemplate($ConfigData,$data);
    }

    public function setQuestion(){
        $this->load->model('SimpleDataManager');
        $id = $this->input->post('id');
        unset($_POST['id']);
        $this->SimpleDataManager->updateItem($id,$this->input->post());
        $this->questions();
    }

    public function payments()
    {
        $data = array();
        $ConfigData = array("ContentTemplate" => "admin/payments");
        $this->layoutmanager->getAdminTemplate($ConfigData,$data);
    }
    public function editStaticPage($id){
        $this->load->model('StaticPage');
        $this->setFormTemplator('editStaticPage','StaticPage');
        $data = array(
            'PageContent' => $this->StaticPage->getPageById($id),
            'ActiveDirectory'   => 'Pages'
        );
        $ConfigData = array("ContentTemplate" => "admin/edit_static_page");
        $this->layoutmanager->getAdminTemplate($ConfigData,$data);

    }
    protected function setFormTemplator($validationScheme,$model){
        if ($this->form_validation->run($validationScheme))
        {
            $Method = $validationScheme;
            $this->load->model($model);
            $this->$model->$Method();
        }
        return false;
    }
    /**
     * admin actions
     * @param  $userId
     * @param  $mark
     * @return void
     */
    public function actionAdmin($userId,$mark){
        $this->Users->actionAdmin($userId,$mark);
        redirect('user/'.$userId);
    }
    public function blockUser($userId,$mark){
        $this->Users->blockUser($userId,$mark);
        redirect('user/'.$userId);
    }
    public function enterUser($userId){
        $this->session->set_userdata('UserID',$userId);
        redirect('user');
    }
    public function updateParentDepartment(){
        $this->load->model('Department');
        $this->Department->setDepartment($this->input->post('id'),$this->input->post('value'),'department_parent');
        echo $this->input->post('value');
    }
    public function updateChildDepartment(){
        $this->load->model('Department');
        $this->Department->setDepartment($this->input->post('id'),$this->input->post('value'),'department');
        echo $this->input->post('value');
    }
    public function addParentDirectory(){
        $this->load->model('Department');
        $this->Department->addDepartment($this->input->post(),'department_parent');
        redirect('admin/departments');
    }
    public function addChildDirectory(){
        $this->load->model('Department');
        $this->Department->addDepartment($this->input->post(),'department');
        redirect('admin/departments');
    }
    public function deleteDepartment($table,$id_department){
        $this->load->model('Department');
        $this->Department->deleteDepartments($table,$id_department);
        redirect('admin/departments');
    }
}
