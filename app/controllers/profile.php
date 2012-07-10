<?php

/**
 * @author trinitron2005@gmail.com
 */

class Profile extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Geo');
        $this->load->model('Users');
    }
    public function projects($userId,$subType= false){
        if($this->session->userdata('UserID') != $userId){
            redirect();
        }
        $this->load->model('ProjectManager');

        $ConfigData = array(    //'wide'   => true,
                                "ContentTemplate" => "index/index",
                                'BlockMainProjectPanel' => true
                                /*'subTemplate' => 'project_list'*/);
        $data['currentUser'] = $this->Users->getExtendedUser($userId);
        $data['activeDirectory'] = 'projects';
        if ($data['currentUser']['common']->type == 2){ // customer
            $data['ProjectList']       = $this->ProjectManager->getList($userId);
            $data['ProjectListAmount']     = $this->ProjectManager->getList($userId,false);

            /*array( 'list'      => $this->ProjectManager->getList($userId),
                                                'amount'    => $this->ProjectManager->getList($userId,false));*/
        }else{                                // freelancer
            $data['ProjectList']       = $this->ProjectManager->getList(false,true,$userId,$subType);
            $data['ProjectListAmount'] = $this->ProjectManager->getList(false,false,$userId,$subType);
            $data['SubTypeSwitch']   = $subType;
            $data['SubTypeSwitchOn'] = true;
            /*array( 'list'      => $this->ProjectManager->getList(false,false,$userId),
                                                'amount'    => $this->ProjectManager->getList(false,false,$userId));*/
        }

        //$this->ProjectManager->setProjectOffersAndAnswers($userId,$data['currentUser']['common']->type);
        if ($this->authmanager->isAdmin() ||  $data['currentUser']['common']->id == $this->session->userdata('UserID')) {
            $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
        }else{
            redirect('main/error');
        }

    }
    public function index($userId,$shop=false){
        if (!$this->Users->isExist($userId)){
            redirect('main/error');
        }
        $this->Users->updateWatches($userId);
        $data = array(
                    'currentUser' => $this->Users->getExtendedUser($userId),
                    'Portfolio'   => $this->Users->getPortfolioFacade($userId,true,false,$shop)
                );
        if ($shop){
            $data['activeDirectory'] = 'shop';
        }else{
            $data['activeDirectory'] = 'portfolio';
        }
        $ConfigData = array(    'wide'   => true,
                                "ContentTemplate" => "profile/index",
                                'subTemplate' => 'portfolio_list');
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    public function statistics($userId){
        /*
        if($this->session->userdata('UserID') != $userId){
            redirect();
        }
         */
        $data = array(
            'currentUser' => $this->Users->getExtendedUser($userId),
            'activeDirectory'   =>  'statistics'
        );
        $ConfigData = array(    'wide'              => true,
                                "ContentTemplate"   => "profile/index",
                                'subTemplate'       => 'statistics');
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    public function portfolio($userId,$portfolioId){
        if ($userId == 'disabled'){
            $userId = $this->Users->getUserIdByPortfolioId($portfolioId);
            if (!$userId) redirect();
        }
        $this->Users->updateWatches($userId);
        $data = array(
                    'currentUser' => $this->Users->getExtendedUser($userId),
                    'Portfolio'   => $this->Users->getPortfolioFacade($userId,true,$portfolioId),
                    'NavigationNumbers' => $this->Users->getPortfolioWithoutId($userId,$portfolioId)
                );


        //var_dump($data);
        if ($data['Portfolio']['list'][0]->shop){
            $data['activeDirectory'] = 'shop';
        }else{
            $data['activeDirectory'] = 'portfolio';
        }

        $ConfigData = array(    'wide'   => true,
                                "ContentTemplate" => "profile/index",
                                'subTemplate' => 'portfolio_item');
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    public function reviews($userId,$subDirectory=3,$page=1)
    {
        $this->setFormTemplator('addReview');
        $data = array(
                    'currentUser'        => $this->Users->getExtendedUser($userId),
                    'reviewsAmountPlus'  => $this->Users->getReviewsAmount($userId,2),
                    'reviewsAmountMinus' => $this->Users->getReviewsAmount($userId,1),
                    'reviewsFromUser'    => $this->Users->getReviewsAmount($userId,false,'id_user_review'),
                    'reviews'            => $this->Users->getReviews($userId,$subDirectory),
                    'activeDirectory'    => 'reviews',
                    'activeSubDirectory' => $subDirectory
                );


        $ConfigData = array(    'wide'   => true,
                                "ContentTemplate" => "profile/reviews");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }


    public function setNicknameWrapper($nickname){
        $id = $this->Users->getUserIdByNickname($nickname);
        if ($id){
            $this->index($id);
        }else{
            redirect('main/error');
        }
    }
    public function checkNickname($nickname){
        if ($this->Users->getUserIdByNicknameProfileEdit($nickname)){
            $this->form_validation->set_message('checkNickname', 'Такой Никнейм уже занят - выберите другой');
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
     * edit current profile
     */
    public function edit(){
        $this->authmanager->isAccessed();
        $this->load->model('Photos');
        $photo = $this->Photos->uploadPhoto('users');
        if ($photo['status']){
            $_POST['picture'] = $photo['insertData']['PhotoLink'];
        }
        //print_r($_FILES);
        //$this->setFormTemplator('editProfile',false,'profile/'.$this->session->userdata('UserID'));
        $user = $this->Users->getUser($this->session->userdata('UserID'));
        $validation = $this->setFormTemplator('editProfile','Users',false );


        $userData = $this->Users->getExtendedUser($this->session->userdata('UserID'));
        $data = array(
                    'CountryList' => $this->Geo->getCountry(),
                    'CityList'    => $this->Geo->getCity($userData['common']->country),
                    'extendedUser'=> $userData
                );
        if ($validation){
            redirect(setLink($data['extendedUser']['common']));    
        }


        $ConfigData = array("ContentTemplate" => "profile/edit");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    protected function setFormTemplator($validationScheme,$model = 'Users',$redirect = false){
        if ($this->form_validation->run($validationScheme))
        {
            $Method = $validationScheme;
            $this->load->model($model);
            $this->$model->$Method();
            if ($redirect){
                redirect ($redirect);
            }

            return true;
        }
        return false;
    }
    public function getExternalUserDepartment()
    {
        $this->load->model('Department');
        $departments = $this->Department->getExternalUserDepartments($this->session->userdata('UserID'));
        echo json_encode($departments);
    }
    public function addDepartment()
    {
        $this->load->model('Department');
        $DepartmentList = $this->input->post('department');
        $type = $this->input->post('departmentType');
        $data['list'] = $this->Department->set($DepartmentList,$type);
        $data['type'] = $type;
        //var_dump($DepartmentList);
        echo json_encode($data);
    }
    public function deleteDepartment(){
        $this->load->model('Department');
        $this->Department->delete($this->input->post('departmentId'),$this->session->userdata('UserID'));
    }
    public function addWork($workType = false){
        $this->load->model('ProjectManager');
        $data = array(
            'projectTimeType'   => $this->ProjectManager->getProjectsTimeType(),
            'projectType'       => $this->ProjectManager->getTypes()
        );

        if ($this->setFormTemplator('addWork'))
        {
            if ($this->input->post('shop') == 1){
                redirect('usershop/'.$this->session->userdata('UserID'));
            }else{
                redirect('profile/'.$this->session->userdata('UserID'));
            }
        }
        //print_r ($this->input->post());
                //die();
        $ConfigData = array(    'workType'        => $workType,
                                "ContentTemplate" => "profile/add_work");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    public function editWork($portfolioId){
        $this->load->model('ProjectManager');
        $portfolio = $this->Users->getPortfolioFacade($this->session->userdata('UserID'),true,$portfolioId);
        $data = array(
            'projectItem'       => $portfolio['list'][0],
            'projectTimeType'   => $this->ProjectManager->getProjectsTimeType(),
            'projectType'       => $this->ProjectManager->getTypes()
        );

        if ($this->setFormTemplator('addWork'))
        {
            if ($data['projectItem']->shop == 1){
                redirect('usershop/'.$this->session->userdata('UserID'));
            }else{
                redirect('profile/'.$this->session->userdata('UserID'));
            }
        }
        //print_r ($this->input->post());
                //die();
        $ConfigData = array("ContentTemplate" => "profile/edit_work");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }

    public function getDepartments()
    {
        $this->load->model('Department');
        $departments = $this->Department->getDepartments();
        echo json_encode($departments);
    }
    public function getParentDepartments()
    {
        $this->load->model('Department');
        $departments = $this->Department->getAjaxParentDepartments();
        echo json_encode($departments);
    }
    public function getChildDepartments()
    {
        $this->load->model('Department');
        $departments = $this->Department->getAjaxDepartments($this->input->post('idParent'));
        echo json_encode($departments);
    }
    public function getChildDepartmentsAutocomplete(){
        $this->load->model('Department');
        $departments = $this->Department->getAjaxDepartmentsAutocomplete($this->input->post('search'));
        echo json_encode($departments);
    }
    public function setUserToUserRating($userId,$mark){
        if ($this->Users->checkUserToUserVoteAbility($userId)) {
            $this->Users->setUserToUserRating($userId,$mark);
        }else{
            $this->session->set_userdata('UserToUserVoteErorrMessage','Вы уже голосовали' );
        }
        redirect ("user/{$userId}");
    }
    public function deleteWork($workId){
        $this->Users->deleteWork($workId);
        redirect('user/'.$this->session->userdata('UserID'));
    }
    public function checkDepartmentsAmount(){
        $user = $this->Users->getUser($this->session->userdata('UserID'));
        $countMaster = count($this->input->post('DepartmentListMaster'));
        $countSlave = count($this->input->post('DepartmentListSlave'));
        //echo $countMaster.$countSlave;
        //die();
        if ($user->expert == 1) {
            $limit['master'] = 5;
            $limit['slave'] = 5;
        }else{
            $limit['master'] = 2;
            $limit['slave'] = 3;
        }
        if ( $countMaster > $limit['master'] || $countSlave > $limit['slave']){
            $this->form_validation->set_message('checkDepartmentsAmount', "Вы можете опубликовать {$limit['master']} основных специализаций и
            {$limit['slave']} дополнительных.");
            return FALSE;
        }else{
            return TRUE;
        }
    }
}
