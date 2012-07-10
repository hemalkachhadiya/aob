<?php
class Project extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProjectManager');
    }
    protected function setFormTemplator($validationScheme){
        if ($this->form_validation->run($validationScheme))
        {
            $Method = $validationScheme;
            $this->ProjectManager->$Method();
        }
        return false;
    }

    public function checkOfferPortfolioAmount($item){
        /*
        var_dump ($item);
        die();
        if ($item == 'test')
		{
			$this->form_validation->set_message('checkOfferPortfolioAmount', 'Вы можете прикрепить не более трех работ из портфолио.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}*/
        return true;
    }
    public function item($id,$page=1)
    {
        $this->setFormTemplator('createOffer');
        $this->ProjectManager->dropOffersAmount($id);
        $data = array(
            'project'           => $this->ProjectManager->get($id),
            'OfferList'         => $this->ProjectManager->getOffersFacade($id,$page),
            'projectTimeType'   => $this->ProjectManager->getProjectsTimeType(),
            'Layout'            => array('Carrousel' => true)
        );
        if ($this->authmanager->isLogged()){
            $data['PortfolioList'] = $this->Users->getPortfolioList($this->session->userdata('UserID'),false,false,false);
            $data['OfferPostAbility'] = $this->checkOffer($id);
        }
        $ConfigData = array("ContentTemplate" => "project/item");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    /**
     * check ability to post for project
     * @return void
     */
    public function checkOffer($projectId)
    {
        $this->load->model('Users');
        $user = $this->Users->getUser($this->session->userdata('UserID'));

        if ($user->FreeOffers > 0 || $user->expert)
		{
            if ($this->ProjectManager->isOfferExists($projectId,$this->session->userdata('UserID'))){
                $this->form_validation->set_message('checkOffer', 'Вы уже подавали заявку на этот проект');
			    return false;
            }else{
                return true;
            }
		}
		else
		{
            $this->form_validation->set_message('checkOffer', 'Превышен лимит на подачу заявок.');
			return false;
		}
    }
    public function checkPortfolioWorks(){
        $PortfolioList = $this->input->post('PortfolioList');
        //print_r ($PortfolioList)
        $result = true;
        if (!empty($PortfolioList)){
            if (count($PortfolioList )>1){
                $tmp = $PortfolioList [1];
                for ($i = 2 ; $i < count($PortfolioList );$i++){
                    if ($tmp == $PortfolioList [$i]){
                        $result = false;
                        $this->form_validation->set_message('checkPortfolioWorks', 'Нельзя прикрепить одну и ту же работу два раза.');
                    }
                    $tmp = $PortfolioList [$i];
                }
            }
        }

        return $result;
    }
    public function setOfferStatus($offerId,$status)
    {
        $project = $this->ProjectManager->checkOfferStatusManagementAbility($offerId,$this->session->userdata('UserID'));
        if ($project['status']){
            $this->ProjectManager->setOfferStatus($offerId,$status);
        }
        redirect ('project/'.$project['id']);
    }
    public function delete($projectId){
        $this->ProjectManager->deleteProject($projectId);
        redirect();
    }
    public function create($id = false)
    {
        if ($this->input->post('submit1')){
            unset($_POST['submit1']);
            $this->load->model('Photos');
            //$photo = $this->Photos->uploadPhoto('users');
            //$_POST['picture']
            $this->ProjectManager->create();
            redirect('main/index');
        }
        $data = array(
            'projectType'       => $this->ProjectManager->getTypes(),
            'projectTimeType'   => $this->ProjectManager->getProjectsTimeType()
        );
        if ($id){
            $data['projectEdit'] = $this->ProjectManager->get($id);
        }else{
            $data['projectEdit'] = array();
        }


        //$data = array_merge($data,$this->TemplateData);
        $ConfigData = array("ContentTemplate" => "project/create");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }

    public function createAjax()
    {
        unset($_POST['submit1']);
        $data['projectId'] = $this->ProjectManager->create(0);
        echo json_encode($data);
    }
    public function close($projectId){
        $this->ProjectManager->closeProject($projectId);
        $this->item($projectId);
    }
    public function save($id){
        $this->ProjectManager->setVisible($id);
        redirect("project/{$id}");
    }
    public function commentOffer(){
        $this->ProjectManager->commentOffer();
        redirect("project/".$this->input->post('id_project'));
    }
    public function getFreeProjects(){
        $this->load->model('Users');
        $user = $this->Users->getUser($this->session->userdata('UserID'));
        if ($user->expert){
            $data['status'] = true;
        }else{
            $data['FreeProjects'] = $user->FreeProjects ;
            if ($user->FreeProjects >0 ){
                $data['status'] = true;
            }else{
                $data['status'] = false;
            }
        }
        echo json_encode($data);
    }
    public function dropOfferNewCommentsAmount(){

        if ($this->authmanager->isLogged()):
            $result = $this->ProjectManager->dropOfferNewCommentsAmount($this->input->post('offerId'),$this->session->userdata('UserID'));
        endif;
        echo json_encode($result);
    }

}