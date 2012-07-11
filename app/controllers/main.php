<?php

class Main extends CI_Controller
{
    private $TemplateData = array();
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_front');
        $this->load->model('PageManager');
        $this->load->model('Users');
    }
    public function changePassword(){
            $password = $this->input->post('password');
            if (!empty($password)){
                $this->Users->changePassword($password);
                $this->session->set_userdata('ChangePassword','Пароль успешно сменен');
            }
            $this->index('login');
        }
    public function editMenu(){
        $this->load->model('LayoutModel');
        $this->LayoutModel->editMenu();
        redirect('menu');
    }
    /*
    public function index()
    {
        if (!$this->authmanager->isLogged()){
            if ($this->input->get('promoCode')){
                echo $this->input->get('promoCode');
                $this->session->set_userdata('promoCode',$this->input->get('promoCode'));
                //http://freewrite/?promoCode=a8fd3ee592caf19b17abc82bcbfbbf48
            }
        }

        $data = array(
            "ProjectList"           => $this->ProjectManager->getList(),
            "ProjectListAmount"     => $this->ProjectManager->getListAmount(),
            'ProjectsAmountPerWeek' => $this->ProjectManager->getAmountPerWeek(),
            'Layout'                => array('Carrousel'=>true)
        );
        $data = array_merge($data,$this->TemplateData);
        $ConfigData = array("ContentTemplate" => "index/index");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }*/
    public function getUsersByDepartment($departmentId,$userType = false){
        $this->load->model('Department');
        $data = array(
            'Layout'                => array('Carrousel'=>true),
            'UsersList'             => $this->Users->getUsersByDepartmentFacade($departmentId,$userType),
            'DepartmentList'        => $this->Department->getBranchName($departmentId),
        );
        $ConfigData = array("ContentTemplate" => "index/department_user_list");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    public function rss() {
        $data = array(
            "ProjectList"           => $this->ProjectManager->getList());
        header("Content-Type: application/rss+xml");
        $this->load->view('content/index/rss',$data);
    }
    public function searchUser()
    {
        $this->load->model('Users');
        $users = $this->Users->searchUser($this->input->post('word'),$this->input->post('id_department'));
        echo json_encode($users);
    }

    public function logout()
    {
        $this->authmanager->logout();
    }
    public function login()
    {
        $userId = $this->Auth_front->checkLogin($this->input->post('login'),$this->input->post('password'));

        if ($userId){
            if ($this->Users->checkBlocked($userId)){
                $result['status'] = false;
                $result['message'] = 'Ваш пользователь заблокирован';
            }else{
                $result['status'] = true;
                $this->session->set_userdata('UserID',$userId);
                if ($this->input->post('expirationTime')){
                    $this->input->set_cookie('expirationTime',true,(time() +60*60*24*14)); // 14 days
                }else{
                    $this->input->set_cookie('expirationTime',true,(time() +60*60*2)); // 2 hours
                }
                $this->Users->setLastLogin($userId);
            }

        }else{
            $result['status'] = false;
            $result['message'] = 'Неправильный логин или пароль';
        }
        echo json_encode($result);
    }
    public function checkLogin(){
        return $this->Auth_front->checkLogin();
    }
	public function createUser(){
        //recaptcha_challenge_field
        //recaptcha_response_field

		$email		 = $this->input->post('login');
		$fPassword	 = $this->input->post('password');
        $type        = $this->input->post('spec');
        $firstName   = $this->input->post('firstName');
        $lastName    = $this->input->post('lastName');
		$response['message'] = '';
		$response['status']  = false;


        if (file_get_contents("http://www.opencaptcha.com/validate.php?ans=".$_POST['code']."&img=".$_POST['img'])!='pass') {
            $response['message'] = "Введите код подтверждения внимательнее";
        } else{
            if ( true !== $this->Auth_front->ifAuthExist(mysql_real_escape_string(trim($email))) ){
                    if (true == $this->Auth_front->createAuth($email,$fPassword,$type,$firstName,$lastName) ){
                        $response['message'] = 'вы были зарегистрированы' ;
                        $response['status']  = true;
                        $userId = $this->Auth_front->checkLogin($email,$fPassword);
                        if ($userId){
                            $this->session->set_userdata('UserID',$userId);
                        }
                        $this->Users->setPersonalDiscount($this->session->userdata('UserID'));
                        if ($this->session->userdata('promoCode')){
                            $this->Users->setUserReferal($this->session->userdata('promoCode'),$this->session->userdata('UserID'));
                            $this->session->unset_userdata('promoCode');
                        };

                        $this->sendMail($email,$fPassword);
                    }
                    else{
                        $response['message'] = 'ошибка базы данных' ;
                    }
            }
            else{
                $response['message'] = 'такой пользователь уже есть в базе';
            }
        }

		echo json_encode($response);
	}
    public function confirmation($confirmation_hash){
        $result = $this->Auth_front->confirmUser($confirmation_hash);
        if ($result) {
            $this->session->set_userdata(array('UserID' => $result));
        }
        redirect('main/index');
    }
    public function test(){

        //$this->sendMail('trinitrin2005@gmail.com','test');
        //echo mail ( 'trinitrin2005@gmail.com' , 'hello world',  'message');
        //$this->load->model('ProjectManager');
        //$this->ProjectManager->setProjectOffersAndAnswers($this->session->userdata('UserID'));
        //require_once('../libraries/Pseudocrypt.php');

    }
    /*
     * функция отправки почты
     */
    public function sendMail($email,$password){
        $confirmation_hash = md5($email.$password);
        $this->load->library('email');
        $this->email->from('info@free-write.com', 'Администрация');
        $this->email->to($email);
        $this->email->subject('Пожалуйста, подтвердите регистрацию на pomada.ua');
        $text = "Уважаемый пользователь pomada.ua,<br>Пожалуйста, подтвердите регистрацию на ".base_url().". Пройдя по ссылке :
                <br/> <a href='".base_url()."main/confirmation/".$confirmation_hash."'>".base_url()."main/confirmation/".$confirmation_hash."</a>";
        $this->email->message($text);
        $this->email->send();
    }
    public function error()
    {
        $this->load->view('content/middle/error');
    }

    public function getPage($name,$wide=false)
    {
        $this->load->model('StaticPage');
        $data = array(
            'PageContent' => $this->StaticPage->getPage($name)
        );

        $ConfigData = array("ContentTemplate" => "index/static_page");
        if ($wide)
        {
            $ConfigData['wide'] = 'true';
        }
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }


    public function loginza()
    {
        $response = file_get_contents("http://loginza.ru/api/authinfo?token={$_REQUEST['token']}");
        //print_r ($response);

        if ($response){
            $user = json_decode($response);

            if (!empty($user->email)){ // if email is set
                $data = array(
                    'email'     => $user->email,
                    'firstName' => $user->name->first_name,
                    'lastName'  => $user->name->last_name,
                    'lastLogin' => date('Y-m-d H:i:s')
                );

            } else if (!empty($user->identity)){ // if email is not set
                $data = array(
                    'identity' => $user->identity,
                    'finished' => 0
                );
            }
            $userId = $this->Users->setLoginzaAuth($data);
            if( $userId ){
                $this->session->set_userdata('UserID',$userId);
            }
        }
        redirect('user');
    }
    public function recover(){
        $email = $this->input->post('email');
        if ($this->Users->checkEmail($email)){
            
            $this->Users->recovery($email);
            $data['message'] = 'На почту выслано письмо для подтверждения';
        }else{
            $data['message'] = 'Такой почты в базе ресурса не существует';
        }
        echo $data['message'] ;
        echo json_encode($data);
    }
    public function finishPasswordRecovery($recovery){
        $userId = $this->Users->checkRecovery($recovery);
        if ($userId){
            $this->session->set_userdata('UserID',$userId);
            $this->input->set_cookie('expirationTime',true,(time() +60*60*2)); // 2 hours
            $this->Users->setLastLogin($userId);
            redirect('user');
        }else{
            redirect('main/error');
        }
    }

    public function setContactUs(){
        $data['message'] = 'Сообщение было отправлено';
        $this->load->model('StaticPage');
        $this->StaticPage->setContactUs($this->input->post());
        echo json_encode($data);
    }
    public function addSubscriberEmail(){
        $this->input->post('email');
        $this->load->model('SlaveModel');
        $data['message'] = 'Такой email уже есть в базе';
        if (!$this->SlaveModel->isSubscriberEmail($this->input->post('email'))){
            $this->SlaveModel->addSubscriberEmail($this->input->post('email'));
            $data['message'] = 'Вы подписаны на рассылку';
        }
        echo json_encode($data);

    }
    public function send_contact_us(){
        $data = array();
        $this->load->library('email');
        $this->email->from($this->input->post('email'), 'Пользователь');
        $this->email->to('Inarts@mail.ru');
        $this->email->subject(base_url().'. Обратная связь');
        $this->email->message($this->input->post('content'));
        if ($this->email->send()){
            $data['message'] = "Сообщение успешно отправлено";
        }else{
            $data['message'] = "Сообщение не было отправлено -  попробуйте ещё раз позже.";
        }
        echo json_encode($data);
    }
    public function index($template = 'index'){
        /*$data = array(
            'ActiveTemplate'    => $template,
            'TemplateData'      => $this->PageManager->get($template)
        );
        $data = array_merge($data,$this->TemplateData);
        $ConfigData = array("ContentTemplate" => "middle/{$template}");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);*/
        $this->getUserCompositeTemplate($template);
    }
    public function getSystemTemplate($template,$menu = false){
        $this->setFormTemplator('editTemplate');

        if ($menu and !$this->PageManager->isTemplate($template)){
            redirect('main/error');
        }
        $data = array(
            'ActiveTemplate'    => $template,
            'TemplateData'      => $this->PageManager->getSystemTemplate($template)
        );

        $data = array_merge($data,$this->TemplateData);
        $ConfigData = array("ContentTemplate" => "middle/template");

        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    public function getUserCompositeTemplate($template){

        $this->setFormTemplator('editTemplate');
        $method = "get_$template";
        $data = array(
            'ActiveTemplate'    => $template,
            'TemplateData'      => $this->PageManager->$method()
        );
        if ($template == 'news_item'){
            $template = 'template';
        }
        $data = array_merge($data,$this->TemplateData);
        $ConfigData = array("ContentTemplate" => "middle/{$template}");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    protected function setFormTemplator($validationScheme){
        if ($this->form_validation->run($validationScheme))
        {
            $Method = $validationScheme;
            $this->PageManager->$Method();
        }
        return false;
    }
    public  function delete($id){
        $this->PageManager->delete($id);
        redirect($this->input->get('redirect'));
    }
    public function add($template){
        $this->PageManager->add($template);
        redirect($this->input->get('redirect'));
    }
    public function editPageById($id){
        $this->setFormTemplator('editTemplate');
        $data = array(
            'ActiveTemplate'    => false,
            'TemplateData'      => $this->PageManager->getItem($id),
            'WideRights'        => true
        );

        $data = array_merge($data,$this->TemplateData);
        $ConfigData = array("ContentTemplate" => "middle/template");

        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    public function getAjaxNewsList(){
        $page = $this->input->post('page');
        $data = array(
            'list'      =>  $this->PageManager->get('news',$page),
            'isMore'    =>  $this->PageManager->get('news',++$page)
        );
        echo json_encode($data);
    }

}