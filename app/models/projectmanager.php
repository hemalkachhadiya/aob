<?php
class ProjectManager extends CI_Model
{
    public function deleteProject($projectId){
        $this->db->where('id',$projectId);
        $this->db->where('id_user',$this->session->userdata('UserID'));
        $this->db->delete('projects');
    }
    public function getProjectsTimeType()
    {
        $query = $this->db->get('projects_time_type');
        return $query->result();
    }

    public function isOfferExists($projectId,$userId)
    {
        $this->db->where('id_project',$projectId);
        $this->db->where('id_user',$userId);
        $query = $this->db->get_where('projects_offer');
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }
    public function createOffer()
    {

        unset($_POST['submit1']);
        $PortfolioList = $this->input->post('PortfolioList');
        unset($_POST['PortfolioList']);
        foreach ($_POST as $key=>$value){
            if (empty($value)){
                unset($_POST[$key] );
            }
        }
        $this->db->insert('projects_offer',$this->input->post());
        $offerId = $this->db->insert_id();/*
        print_r($_POST);
        print_r ($PortfolioList); */
        //die();
        if (!empty($PortfolioList)){

            foreach ($PortfolioList as $item){
                if ($item){
                 $this->db->insert('projects_offer_portfolio', array(
                                                                 'id_offer' => $offerId,
                                                                 'id_portfolio' => $item
                                                               ));
                }

            }

        }
    }
    /**
     * retrieve single project data
     * @param  $id
     * @return
     */
    public function get($id)
    {
        $this->db->select ('        projects.description,
                                    projects.title,
                                    users.id as userId,
                                    users.type as type,
                                    users.expert as  expert,
                                    nickname,
                                    projects.id as projectId,
                                    users.firstName,
                                    users.email,
                                    users.lastName,
                                    users.picture,
                                    projects.id_user,
                                    c_title as currency,
                                    projects.id_currency,
                                    projects.id_type,
                                    projects.deadline,
                                    projects.price,
                                    projects.expert,
                                    projects.createTime,
                                    projects.visible,
                                    projects.projectTop,
                                    projects.file_name,
                                    projects.file_path,

                                    closed');
        $this->db->from('projects');
        $this->db->join('users','users.id=projects.id_user','left');
        $this->db->join('currency','currency.id=projects.id_currency','left');


        $this->db->where(array('projects.id'=>$id));

        $query = $this->db->get();
        $project = $query->row();
        if (!empty($project)){
                $project->offersAmount = $this->getProjectOffersAmount($id);
                $this->load->model('Department');
                $project->departmentList = $this->Department->getProjectDepartments($project->projectId);
        }

        return $project;
    }

    public function getProjectOffersAmount($projectId){
        $this->db->where('id_project',$projectId);
        $query = $this->db->get_where('projects_offer');
        if($this->db->affected_rows()>0){
            return $this->db->affected_rows();
        }else{
            return 0;
        }
    }
    /**
     * create project
     * @return void
     */
    public function create($visible = 1)
    {
        $DepartmentList = $this->input->post('DepartmentList');
        unset($_POST['DepartmentList']);
        $projectId = $this->input->post('id_project');
        unset($_POST['id_project']);
        $data = $this->input->post();
        $data['description'] = nl2br($data['description']);
        $data['id_user'] = $this->session->userdata('UserID');
        $data['visible'] = $visible;
        if (!empty($_FILES["workFile"]) and !empty($_FILES["workFile"]["name"])
            & ($_FILES["workFile"]["size"] < 1000000))
        {
            $fileName = md5(time()).$_FILES["workFile"]["name"];
            $result = move_uploaded_file($_FILES["workFile"]["tmp_name"],"./import/projects/".$fileName);
            //var_dump ($result);
            //die();
            $data['file_path'] = $fileName;
            $data['file_name'] = $_FILES["workFile"]["name"];
        }
        if (empty($projectId)){

            $this->db->insert('projects',$data);
            $projectId = $this->db->insert_id();
        }else{
            $this->db->where('id',$projectId);
            $this->db->update('projects',$data);
            $this->db->where('id_project',$projectId);
            $this->db->delete('department_projects');
        }
        if (!empty($DepartmentList )){
            foreach ($DepartmentList as $item) {
                $this->db->insert('department_projects',array('id_project' => $projectId,'id_department' => $item));
            }
        }
        return $projectId;
    }
    public function getProjectListByOffer($userId){

    }
    public function getList($userId = false,$limit = true,$byOffer = false,$subType = false)
    {
        if ($this->input->get('page')){
            $page = $this->input->get('page');
        }else{
            $page = 1;
        }
        $this->db->select ('projects.description,
                            projects.title,
                            users.id as userId,
                            projects.id as projectId,
                            users.firstName,
                            users.email,
                            users.lastName,
                            nickname,
                            c_title  as currency,
                            projects.deadline,
                            projects.price,
                            projects.expert,
                            projects.createTime,
                            projects_type.id as id_type,
                            projects_type.name as workType,
                            projectTop');
        $this->db->from('projects');
        $this->db->order_by('projects.projectTop','DESC');
        $this->db->order_by('projects.createTime','DESC');
        if ($limit){
            $this->db->limit(SEARCH_RESULTS, ($page-1)*SEARCH_RESULTS);
        }
        if ($userId){ // fork for user portfolio
            $this->db->where('id_user',$userId);
        }
        if ($byOffer){
            $this->db->join('projects_offer','projects_offer.id_project=projects.id','left');
            $this->db->where('projects_offer.id_user',$byOffer);
            if ($subType == 1 || $subType == 2) {
                $this->db->where('projects_offer.status',$subType);
            }elseif ($subType == 3){
                $this->db->where('projects_offer.status',0);
            }
        }
        $this->db->join('users','users.id=projects.id_user','left');
        $this->db->join('projects_type','projects_type.id=projects.id_type','left');
        $this->db->join('currency','currency.id=projects.id_currency','left');

        $this->db->where('visible',1);
        $query = $this->db->get();
        $projectList = $query->result();
        $rowsAmount = $this->db->affected_rows();
        $this->load->model('Department');
        foreach ($projectList as $item){
            // counting project offers for logged users
            // in individual projects view
            if ($this->authmanager->isLogged()):
                $this->load->model('Users');
                $user = $this->Users->getUser($this->session->userdata('UserID'));
                $item->OffersAndCommentsAmount = $this->getProjectOffersAndAnswers($user->id,$user->type,$item->projectId);
            endif;

            $item->departmentList = $this->Department->getPortfolioDepartments($item->projectId);
            $item->offerAmount = $this->getProjectOffersAmount($item->projectId);
        }
        if ($limit){
            return $projectList;
        }else{
            return $rowsAmount;
        }

    }
    public function getListAmount($userId = false)
    {
        if ($userId){
            $this->db->where('id_user',$userId);
        }
        $this->db->from('projects');
        $this->db->join('users','users.id=projects.id_user','left');
        $this->db->where('visible',1);
        $query = $this->db->get();
        return $this->db->affected_rows();
    }

    public function getTypes()
    {
        $query = $this->db->get('projects_type');
        if($this->db->affected_rows()>0){
            return $query->result();
        }else{
            return false;
        }
    }
    public function checkOfferStatusManagementAbility($projectOfferId,$userId)
    {
        $preQuery = $this->db->get_where('projects_offer',array('id'=>$projectOfferId));
        $projectId = $preQuery->row()->id_project;
        $query = $this->db->get_where('projects',array('id' => $projectId,'id_user'=>$userId));
        if($this->db->affected_rows()>0){
            return array('status'=>true,'id'=>$projectId);
        }else{
            return array('status'=>false,'id'=>$projectId);
        }
    }
    public function getAmountPerWeek()
    {
        $this->db->where('visible',1);
        $query = $this->db->get_where('projects','createTime >= DATE_SUB(CURRENT_TIMESTAMP,INTERVAL 7 DAY)');

        if($this->db->affected_rows()>0){
            return $this->db->affected_rows();
        }else{
            return 0;
        }
    }
    public function getOffersFacade($projectId,$page)
    {
        $data = array(
            'list' => $this->getOffers($projectId,$page),
            'amount' => $this->getProjectOffersAmount($projectId)
        );
        return $data;
    }
    public function getUserOffersAmount($id){
        $this->db->where('id_user',$id);
        $this->db->get('projects_offer');
        return $this->db->affected_rows();
    }
    public function getOffers($projectId,$page)
    {
        $this->db->select ('      projects_offer.id as offerId,
                                  projects_offer.id_user,
                                  projects_offer.id_project	,
                                  projects_offer.createTime	,
                                  projects_offer.account_from	,
                                  projects_offer.account_to	,
                                  projects_offer.time_from	,
                                  projects_offer.time_to	,
                                  projects_offer.comment	,
                                  projects_offer.link	,
                                  projects_offer.status	,
                                  projects_offer.picture	,
                                  projects_offer.id_currency,
                                  currency.c_title as currency,
                                  users.firstName,
                                  users.lastName,
                                  nickname,
                                  users.picture,
                                  users.type,

                                  users.expert,
                                  c_title,
                                  c_abbr,
                                  one,
                                  many,
                                  middle
                                    ');
        $this->db->from('projects_offer');
        $this->db->join('users','users.id=projects_offer.id_user','left');
        $this->db->join('currency','currency.id=projects_offer.id_currency','left');

        $this->db->join('projects_time_type','projects_time_type.id=projects_offer.id_time_type','left');
        
        $this->db->limit(SEARCH_RESULTS, ($page-1)*SEARCH_RESULTS);
        $this->db->where(array('id_project' => $projectId));

        $query = $this->db->get();
        $offers = $query->result();
        $this->load->model('Department');
        $this->load->model('Users');
        foreach ($offers as $item){
            $item->departmentList = $this->Department->getUserDepartments($item->id_user,1,false,3);
            $item->rating = $this->Users->getUserRating($item->id_user);
            //$item->rating = $this->Users->getByRating($item->id_user);
            $item->portfolio = $this->getOffersPortfolio($item->offerId);
            $item->commentList = $this->getOfferComments($item->offerId);

            if ($this->authmanager->isLogged()):
                $item->NewCommentsAmount =  $this->getOfferNewCommentsAmount($item->offerId,$this->session->userdata('UserID'));
            endif;
        }
        return $offers;
    }
    public function getOffersPortfolio($offerId){
        $this->db->from('projects_offer_portfolio');
        $this->db->join('users_portfolio','projects_offer_portfolio.id_portfolio = users_portfolio.id','left');
        $this->db->where('id_offer',$offerId);
        $query = $this->db->get();
        if ($this->db->affected_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    public function setOfferStatus($offerId,$status)
    {
        $this->db->where('id',$offerId);
        $this->db->update ('projects_offer',array('status'=>$status));
    }
    public function closeProject($projectId){
        $this->db->where('id',$projectId);
        $this->db->update('projects',array('closed' => 1));
    }
    public function setVisible($projectId){
        $this->db->where('id',$projectId);
        $this->db->update('projects',array('visible' => 1));
    }
    public function commentOffer(){
        $this->db->insert('projects_offer_comment',array(
                                                    'id_user' => $this->session->userdata('UserID'),
                                                    'comment' => $this->input->post('comment'),
                                                    'id_offer' => $this->input->post('id_offer'),
                                                    'newComment' => $this->input->post('newComment')
                                                   ));
    }
    public function getOfferComments($id_offer){
        $this->db->from('projects_offer_comment');
        $this->db->join('users','projects_offer_comment.id_user = users.id','left');
        $this->db->where('id_offer', $id_offer);
        $query = $this->db->get();

        return $query->result();
    }
    public function getOfferNewCommentsAmount($id_offer,$userId){
        $this->load->model('Users');
        $user = $this->Users->getUser($userId);
        //print_r($user);
        $revertType = array(0,2,1);
        $this->db->from('projects_offer');
        $this->db->join('projects_offer_comment','projects_offer.id = projects_offer_comment.id_offer','left');
        $this->db->where('newComment',$revertType[$user->type]);
        $this->db->where('projects_offer.id',$id_offer);
        $this->db->get();
        return $this->db->affected_rows();
    }
    public function dropOfferNewCommentsAmount($id_offer,$userId){
        $this->load->model('Users');
        $user = $this->Users->getUser($userId);
        //print_r($user);
        $revertType = array(0,2,1);

        $this->db->set('newComment',0);
        $this->db->where('newComment',$revertType[$user->type]);
        $this->db->where_in('id_offer',$id_offer);
        $this->db->update('projects_offer_comment');
        return $this->db->affected_rows();

    }
    /**
     * get amount of offers or amount of comments for customer
     * projects or project
     * @param $userId
     * @param $projectId
     * @param $entytyType
     * @return mixed
     */
    private function getCustomerProjectOffersAndAnswersAmount($userId,$projectId,$entityType){
        $this->db->from('projects');
        $this->db->join('projects_offer','projects_offer.id_project = projects.id','left');
        $this->db->join('projects_offer_comment','projects_offer.id = projects_offer_comment.id_offer','left');
        $this->db->where('projects.id_user',$userId);
        $this->db->where($entityType,1);

         //if user has commented
        if ($projectId){
            $this->db->where('projects.id',$projectId);
        }
        $this->db->get();
        return $this->db->affected_rows();
    }
    public function getProjectOffersAndAnswers($userId,$type,$projectId = false){

        if ($type == 2){ // customer
            $amount =   $this->getCustomerProjectOffersAndAnswersAmount($userId,$projectId,'newOffer')
                        +$this->getCustomerProjectOffersAndAnswersAmount($userId,$projectId,'newComment');

        }else{ //freelancer
            $this->db->from('projects_offer');
            $this->db->join('projects_offer_comment','projects_offer.id = projects_offer_comment.id_offer','left');
            $this->db->where('projects_offer.id_user',$userId);
            $this->db->where('newComment',2); // if customer has commented
            if ($projectId){
                $this->db->where('projects_offer.id_project',$projectId);
            }
            $this->db->get();
            $amount = $this->db->affected_rows();
        }
        return $amount;
    }
    public function dropOffersAmount($projectId){
        if ($this->authmanager->isLogged()) :
            $this->db->get_where('projects',array(
                'id'        => $projectId,
                'id_user'   => $this->session->userdata('UserID')
            ));
            if ( $this->db->affected_rows() > 0 ) :
                $this->db->set('newOffer',0);
                $this->db->where('newOffer',1);
                $this->db->where_in('projects_offer.id_project',$projectId);
                $this->db->update('projects_offer');
            endif;
        endif;
    }

    public function setProjectOffersAndAnswers($userId,$type){
        if ($type == 2){ // customer
            //where
            $query = $this->db->get_where('projects',array('id_user' => $userId));
            $projectList = $this->turnToArray($query->result());
            if (!empty($projectList)){
                $this->db->where_in('id_project',$projectList);
                $query = $this->db->get_where('projects_offer');
                $offerList = $this->turnToArray($query->result());
                if (!empty($offerList)){
                    $this->db->where_in('id_offer',$offerList);
                    $query = $this->db->get_where('projects_offer_comment');
                    $commentList = $this->turnToArray($query->result());

                    //update
                    $this->db->set('newOffer',0);
                    $this->db->where('newOffer',1);
                    $this->db->where_in('id',$offerList);
                    $this->db->update('projects_offer');
                    if (!empty($commentList)){
                        $this->db->set('newComment',0);
                        $this->db->where('newComment',1);
                        $this->db->where_in('id',$commentList);
                        $this->db->update('projects_offer_comment');
                    }

                }
            }
        }else{
            $query = $this->db->get_where('projects_offer',array('id_user' => $userId));
            $offerList = $this->turnToArray($query->result());
            if (!empty($offerList)){
                $this->db->set('newComment',0);
                $this->db->where_in('id_offer',$offerList);
                $this->db->where('newComment',2);
                $this->db->update('projects_offer_comment');
            }
            

        }
    }
    public function turnToArray($list){
        $data = array();
        foreach ($list as $item){
            $data[] = $item->id;
        }
        return $data;
    }
}