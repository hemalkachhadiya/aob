<?php


class Users extends CI_Model {
    public function editProfile()
    {
        if ($this->input->post('password_change')){
            $_POST ['password'] = md5($this->input->post('password_change'));
        }
        unset ($_POST['password_change']);
        unset ($_POST['password_change2']);

        $departmentMaster = $this->input->post('DepartmentListMaster');
        $departmentSlave = $this->input->post('DepartmentListSlave');
        unset ($_POST['DepartmentListMaster']);
        unset ($_POST['DepartmentListSlave']);

        $this->load->model('Department');
        $this->Department->editUserDepartmentList($departmentMaster,$this->session->userdata('UserID'),1);
        $this->Department->editUserDepartmentList($departmentSlave,$this->session->userdata('UserID'),2);

        $this->db->where('id',$this->session->userdata('UserID'));
        $this->db->update('users',$this->input->post());
    }
    public function getUserIdByNickname($nickname){
        $query = $this->db->get_where('users',array('nickname'=>$nickname));

        if($this->db->affected_rows()>0){
            return $query->row()->id;
        }else{
            return false;
        }
    }
    public function getUserIdByNicknameProfileEdit($nickname){
        $query = $this->db->get_where('users',array('nickname'=>$nickname,'id !=' => $this->session->userdata('UserID')));

        if($this->db->affected_rows()>0){
            return $query->row()->id;
        }else{
            return false;
        }
    }
    public function isExist($userId){
        $query = $this->db->get_where('users',array('id' => $userId));
        if($this->db->affected_rows()>0){
                    return true;
                }else{
                    return false;
                }
    }
    /**
     * get users  by rating
     */
    public function getByRating($userId = false,$config = array('payedOrder' => true))
    {
        //echo "het_by_rating_call<br>";
        if ($userId){
            $this->db->where('id',$userId);
        }
        $this->db->select('

           '.$this->getRatingSelect().',
           firstName,
           lastName,
           id,
           nickname,
           picture,
           expert,
           topUser_comment
        ');
        $this->load->model('Department');
        $this->db->limit(15,0 );
        if ($config['payedOrder']){
            if (!$userId){
                $this->db->where('topUser>now()');
                $this->db->order_by('topUser','DESC');
            }
        }

        $this->db->order_by('currentRating','DESC');
        $query = $this->db->get('users');

        $usersList = $query->result();
        foreach ($usersList as $item){
            $item->departmentList = $this->Department->getUserDepartments($item->id,1,false,3);
        }

        if ($userId){
            //var_dump($usersList);
            return $usersList[0]->currentRating;
        }else{
            return $usersList;
        }
    }
    public function getUserByMoney($terms)
    {
        if ($terms == 'day'){
            $terms = " and DATE(users_payment.creatTime) = CURDATE()";
        }else if ($terms == 'standard'){
            $terms = '';
        }
        $this->db->select('

           '.$this->getRatingByMoneySelect($terms).',
           firstName,
           lastName,
           id,
           nickname,
           picture,
           expert,
           type
        ');

        $this->db->limit(15,0 );
        $this->db->order_by('currentRating','DESC');
        $query = $this->db->get('users');
        $usersList = $query->result();

        return $usersList;
    }

    public function getUserRating($userId){
        $this->db->select($this->getRatingSelect());
        $this->db->where('id',$userId);
        $user = $this->db->get('users');
        return $user->row()->currentRating;
    }
    public function getRatingByMoneySelect($terms){
        $query = "
                (
                    (select
                        sum(mig_sum)
                     from
                        users_payment
                     where
                        users_payment.id_user <> users_payment.id_user_owner
                     and
                        users_payment.id_user = users.id
                     and mig_sum>0
                     {$terms})
                   ) as currentRating";
                return $query;
    }
    public function getRatingSelect(){
        $query = '
        (
            (25*(select count(id) from users_portfolio where users_portfolio.id_type = '.TYPE_DIPLOM.' and users_portfolio.id_user = users.id))
           +(10*(select count(id) from users_portfolio where users_portfolio.id_type = '.TYPE_REFERAT.' and users_portfolio.id_user = users.id))
           +(15*(select count(id) from users_portfolio where users_portfolio.id_type = '.TYPE_KURSOVAIA.' and users_portfolio.id_user = users.id))
           +(50*(select count(*) from projects_offer where projects_offer.status =2 and projects_offer.id_user = users.id))
           +(20*(select count(*) from users_review where id_user = users.id and mark = 2))
           -(30*(select count(*) from users_review where id_user = users.id and mark = 1))
           +(10*(select count(*) from users_review where id_user_review = users.id ))
           ) as currentRating';
        return $query;
    }
    public function getUser($id){
        $this->db->where('users.id', $id);
        $query = $this->db->get('users');
        $user = $query->row();
        $user->countryTitle = $this->getCountryTitle($user->country);
        /*
        $user->FreeOffers = FREE_WEEK_OFFER_AMOUNT - $this->getUserOffers($user->id);
        $user->FreeProjects = FREE_WEEK_PROJECT_AMOUNT - $this->getUserProjects($user->id);

        $this->load->model('MessageManager');
        $user->UnreadMailAmount = $this->MessageManager->getUnreadAmount($user->id);
        $user->PortfolioAmount = $this->getPortfolioAmount($user->id);
        $user->ShopAmount = $this->getPortfolioAmount($user->id,true);
        $user->Rating = $this->getByRating($user->id);
        $user->SecondRating = $this->getUserToUserRating($user->id);
        $user->balance = $this->getBalance($user->id);
        $this->load->model('ProjectManager');
        $user->ProjectOffersAndAnswers = $this->ProjectManager->getProjectOffersAndAnswers($user->id,$user->type);
        if ($user->type == 2){  // published projects
            $user->ProjectAmount  = $this->ProjectManager->getListAmount($user->id);

        }else{                  // setted offers
            $user->ProjectAmount  = $this->ProjectManager->getUserOffersAmount($user->id);
        }
        */
        return $user;
    }
    public function getBalance($userId){
        $this->db->where('id_user',$userId);
        $this->db->where('status',2); // successed payment
        $this->db->select('sum(mig_sum) as balance');
        $query = $this->db->get('users_payment');
        $balance = $query->row()->balance;
        if ($balance){
            return $balance;
        }else{
            return 0;
        }

    }
    public function getCountryTitle($countryId)
    {
        if ($countryId){
            $this->db->where('id_country',$countryId);
            $query = $this->db->get('geo_country');
            return $query->row()->name;
        }else{
            return false;
        }
    }
    /**
     * get user's offers amount
     * @param  $id
     * @return int
     */
    public function getUserOffers($id)
    {
        $this->db->where('id_user',$id);
        $query = $this->db->get_where('projects_offer','year(createTime) = year(now()) and week(createTime, 1) = week(now(), 1)');
        if($this->db->affected_rows()>0){
            return $this->db->affected_rows();
        }else{
            return 0;
        }
    }
    public function getUserProjects($id)
    {
        $this->db->where('id_user',$id);

        $query = $this->db->get_where('projects','year(createTime) = year(now()) and week(createTime, 1) = week(now(), 1)');
        if($this->db->affected_rows()>0){
            return $this->db->affected_rows();
        }else{
            return 0;
        }
    }

    public function getExtendedUser($id){
        $this->load->model('Department');
        $data = array(
            'common'        => $this->getUser($id),
            'masterSpec'    => $this->Department->getUserDepartments($id,1),
            'slaveSpec'     => $this->Department->getUserDepartments($id,2),
            'rating'        => $this->getByRating($id)
        );
        if ($this->authmanager->isLogged()){
            $data['UserToUserVoteAbility'] = $this->checkUserToUserVoteAbility($id);
        }

        return $data;
    }

    /**
     * for changing passwords in account
     * @param  $UserID
     * @return
     */
    public function getPasswordByID($UserID){
        $this->db->where('id',$UserID);
        $query = $this->db->get('users');
        return $query->row()->auth_pass_hash;
    }

    /**
     * template function for saving data
     * @param  $Data
     * @param  $UserID
     * @return void
     */
    // todo sha1 auth_email
    public function saveContactInformation($UsersInfoData,$UserID){
        $TmpArray = array('firstName','lastName','email','SecurityQuestionID','SecurityAnswer');

        foreach ($TmpArray as $item){
            $Data[$item] = $UsersInfoData[$item];
            unset($UsersInfoData[$item]);
        }
        $this->db->where('id', $UserID);
        $this->db->update('users', $Data);

        $this->db->where('UserID', $UserID);
        $this->db->update('users_info', $UsersInfoData);
        
    }
    public function saveChangePassword($UserData,$UserID){
        //print_r ($UserData);
        $Data = array (
            "auth_pass_hash"        => sha1( "right" . md5($UserData['NewPassword']) . "left" )
            //"SecurityQuestionID"    => $UserData["SecurityQuestionID"],
            //"SecurityAnswer"        => $UserData["SecurityAnswer"]
        );
        //print_r ($Data);
        $this->db->where('id', $UserID);
        $this->db->update('users', $Data);
    }
    public function saveCommunicationSettings($UserData,$UserID){
        // setting type of delivery
        $Data['DeliveryID'] = $UserData['DeliveryID'];
        unset($UserData['DeliveryID']);
        $this->db->where('id', $UserID);
        $this->db->update('users_info', $Data);
        //delete old settings

        $this->db->where('UserID',$UserID);
        $this->db->delete ("users_communication_connections");

        //set new settings
        if (!empty($UserData['SettingsID'])){
            foreach ($UserData['SettingsID'] as $item){
                $TmpData = array (
                    'SettingsID'    => $item,
                    'UserID'        => $UserID
                );
                $this->db->insert('users_communication_connections',$TmpData);
            }    
        }
    }
    public function saveCloseAccount($UserData,$UserID){
        //var_dump($UserData);
        $InsertID = 0;
        if ($UserData['CloseAccountCause'] == 'other'){
            if (!empty($UserData['OtherCause'])) {
                $Data['title'] = $UserData['OtherCause'];
            }else{
                $Data['title'] = "other";
            }
            $this->db->insert('close_account_cause',$Data);
            $InsertID = $this->db->insert_id();
        }else {
            $InsertID = $UserData['CloseAccountCause'];
        }

        $this->db->where('id', $UserID);
        $this->db->update('users', array('CloseAccountCauseID' => $InsertID));
    }

    /**
     * get info about one single company
     * @param $UserID
     * @param string $fields
     * @return bool|mixed
     */
    public function getCompanyInfo($UserID, $fields='*'){
	
		$this->db->select($fields);
        $this->db->where('UserID',$UserID);
        $this->db->from('companies');
        $this->db->join('geo_state', 'companies.StateID = geo_state.id','left');

        $query =  $this->db->get();
        if ($this->db->affected_rows() > 0 ){
            return $query->row();
        }else {
            return false;
        }
    }
    public function saveBusinessInformation($CompanyData, $UserID){
        //print_r ($CompanyData);die();
        if (!empty($CompanyData['attribute'])){
            $hours  = $CompanyData['attribute'];
            unset($CompanyData['attribute']);
            $preAttribute = array();
            for($i=0; $i<count($hours) ;$i+=3){
                    $preAttribute[] = join("|",array($hours[$i],$hours[$i+1],$hours[$i+2]));
            }
            $CompanyData['sHours'] = ("|" == trim(join(",",$preAttribute)))? "":join(",",$preAttribute);
        }else{
            $CompanyData['sHours'] ='';
        }

        
        $this->db->where('UserID', $UserID);
        $this->db->update('companies', $CompanyData);
    }
    public function setPassword($password){
        $data = array (
            'auth_pass_hash' => sha1( "right" . md5($password) . "left" )
        );
        $this->db->where('id', $this->session->userdata('UserID'));
        $this->db->update('users', $data);
    }
    public function checkAjaxLogIn($whereData){
        $this->db->where($whereData);
        $query = $this->db->get('users');
        if ($this->db->affected_rows() > 0 ){
            return $query->row();
        }else {
            return false;
        }
    }
    public function getCarrousel($page = 1)
    {
        $data = array(
            'list'  => $this->getCarrouselList($page),
            'amount'=> $this->getCarrouselAmount()
        );
        return $data;
    }
    public function getCarrouselList($page)
    {
        $this->db->from('users_carrousel');
        $this->db->join('users','users_carrousel.id_user = users.id','left');
        $this->db->limit(CARROUSEL_RESULTS, ($page-1)*CARROUSEL_RESULTS);
        $this->db->order_by('createTime','DESC');
        $query  = $this->db->get();
        $usersList = $query->result();
        $this->load->model('Department');
        foreach ($usersList as $item){
            $item->departmentList = $this->Department->getUserDepartments($item->id_user,1,false,3);
        }
        return $usersList;
    }
    public function getCarrouselAmount()
    {
        $this->db->from('users_carrousel');
        $this->db->join('users','users_carrousel.id_user = users.id','left');
        $this->db->order_by('createTime','DESC');
        $query  = $this->db->get();
        $usersList = $query->result();
        $this->load->model('Department');
        foreach ($usersList as $item){
            $item->departmentList = $this->Department->getUserDepartments($item->id_user,1);
        }
        return $usersList;

    }
    public function setLoginzaAuth($data)
    {
        if (!empty($data['email'])){
            $this->db->where('email',$data['email']);
        }else{
            $this->db->where('identity',$data['identity']);
        }
        $query = $this->db->get('users');
        if ($this->db->affected_rows() > 0 ){
            return $query->row()->id;
        }else {
            $this->db->insert('users',$data);
            return $this->db->insert_id();
        }
    }
    public function setLoginzaAuthByIdentity($identity){

    }
    public function getReviewsAmount($userId,$mark,$userType = 'id_user')
    {
        $this->db->where($userType,$userId);
        if ($mark){
            $this->db->where('mark',$mark);
        }
        $query = $this->db->get('users_review');
        if ($this->db->affected_rows() > 0 ){
            return $this->db->affected_rows();
        }else {
            return 0;
        }
    }

    public function getReviews($userId,$subDirectory){
        $data = array(
            'list' => $this->getReviewsList($userId,$subDirectory),
            'amount' => ($this->getReviewsAmount($userId,1)+$this->getReviewsAmount($userId,2))
        );
        return $data;
    }
    public function getReviewsList($userId,$subDirectory=3)
    {
        $from = 'id_user_review';
        $to = 'id_user';
        if ($subDirectory == 4){
            $to = 'id_user_review';
            $from = 'id_user';
        }
        $this->db->select('
                            id_user,
                            id_user_review,
                            firstName,
                            lastName,
                            picture,
                            users_review.id as reviewId,
                            mark,
                            nickname,
                            createTime,
                            comment
        ');
        $this->db->from('users_review');
        $this->db->join('users', 'users.id = users_review.'.$from,'left');
        $this->db->where ('users_review.'.$to,$userId);
        $this->db->order_by ('users_review.createTime','DESC');
        if ($subDirectory < 3){
            $this->db->where('mark' , $subDirectory);
        }
        //$this->db->limit(SEARCH_RESULTS, ($page-1)*SEARCH_RESULTS);
        $query = $this->db->get();
        $reviewList = $query->result();

        if ($this->db->affected_rows() > 0 ){
            foreach($reviewList as $review){
                $review->displayedUserId = $review->$from;
            }
            return $reviewList;
        }else {
            return false;
        }
    }
    public function getPortfolioFacade($userId,$page,$portfolioId = false,$shop = false){
        $data =  array(
            'list'   => $this->getPortfolioList($userId,$page,$portfolioId,$shop),
            'amount' => $this->getPortfolioAmount($userId,$shop)
        );
        //print_r ($data);
        return $data;
    }
    public function getUsersByDepartmentFacade($departmentId,$userType){
        $data =  array(
            'list'   => $this->getUsersByDepartment($departmentId,$userType,true),
            'amount' => $this->getUsersByDepartment($departmentId,$userType,false)
        );
        //print_r ($data);
        return $data;
    }
    public function getUsersByDepartment($departmentId,$userType,$limit){
        $this->db->select('
           (
           (25*(select count(id) from users_portfolio where users_portfolio.id_type = '.TYPE_DIPLOM.' and users_portfolio.id_user = users.id))
           +(10*(select count(id) from users_portfolio where users_portfolio.id_type = '.TYPE_REFERAT.' and users_portfolio.id_user = users.id))
           +(15*(select count(id) from users_portfolio where users_portfolio.id_type = '.TYPE_KURSOVAIA.' and users_portfolio.id_user = users.id))
           +(50*(select count(*) from projects_offer where projects_offer.status =2 and projects_offer.id_user = users.id))
           +(20*(select count(*) from users_review where id_user = users.id and mark = 2))
           -(30*(select count(*) from users_review where id_user = users.id and mark = 1))
           +(10*(select count(*) from users_review where id_user_review = users.id ))
           )as rating,
           firstName,
           lastName,
           id,
           nickname,
           picture,
           expert,
           createDate,
           users.id  as id_user,
           type
        ');
        $this->db->order_by('rating','DESC');

        $this->db->from('users');
        $this->db->join('department_users','department_users.id_user=users.id','left');
        $this->db->where('id_department',$departmentId);
        if ($limit){
            $page = $this->input->get('page');
            if (!$page) $page = 1;
            $this->db->limit(SEARCH_RESULTS, ($page-1)*SEARCH_RESULTS);
        }
        if ($userType){
            $this->db->where('users.type',$userType);
        }
        $query = $this->db->get();
        if ($limit){
            $list = $query->result();
            $this->load->model('Department');
            if (!empty($list)){
                foreach ($list as $item){
                    $item->departmentList = $this->Department->getUserDepartments($item->id_user,false);
                    $item->portfolioList  = $this->getPortfolioList($item->id_user,false,false);

                    //$item->rating = $this->getByRating($item->id_user);
                }
            }
            return $list;
        }else{
            return $this->db->affected_rows();
        }
    }

    public function getPortfolioWithoutId($userId,$portfolioId){
        $this->db->select('id');
        //$this->db->where_not_in('id',array($portfolioId));
        $this->db->where('id_user',$userId);
        $query = $this->db->get('users_portfolio');
        $data = array();
        $data['next'] = 0 ;
        $data['prev'] = 0 ;
        $list = $query->result();

        
        if ($this->db->affected_rows() > 1){
            foreach ($list as $i => $item){
                if ($list[$i]->id == $portfolioId){

                    if (!empty($list[$i+1]) && !empty($list[$i-1])){
                        $data['prev'] =  $list[$i-1]->id; $data['next'] = $list[$i+1]->id;
                    }else
                    if (!array_key_exists(($i+1),$list) && !array_key_exists(($i-2),$list) && array_key_exists(($i-1),$list)){
                        $data['prev'] /*= $data['next'] */= $list[$i-1]->id;
                    }else
                    if (!array_key_exists(($i-1),$list) && !array_key_exists(($i+2),$list) && array_key_exists(($i+1),$list)){
                        /*$data['prev'] = */$data['next'] = $list[$i+1]->id;
                    }else
                    if (!array_key_exists(($i-1),$list) && array_key_exists(($i+2),$list) && array_key_exists(($i+1),$list)){
                        /*$data['prev'] = $list[$i+2]->id;*/ $data['next'] = $list[$i+1]->id;
                    }else
                    if (array_key_exists(($i-1),$list) && array_key_exists(($i-2),$list) && !array_key_exists(($i+1),$list)){
                        $data['prev'] = $list[$i-1]->id;/* $data['next'] = $list[$i-2]->id;*/
                    }
                }
            }
        }
        return $data;
    }
    /**
     * @param bool $userId
     * @param bool $page
     *  for user profile set to true to watch all works
     *  for
     * @param  $portfolioId
     * @return
     */
    public function getPortfolioList($userId=false,$page = false,$portfolioId,$shop=false)
    {
        $this->db->select('
            users_portfolio.id as workId,
        	users_portfolio.id_user,
        	users_portfolio.title,
        	users_portfolio.workFile,
        	users_portfolio.description,
        	users_portfolio.price,
        	users_portfolio.id_currency,
        	c_title as currency,
        	users_portfolio.picture,
        	users_portfolio.id_type,
        	users_portfolio.shop,
        	users_portfolio.createTime,
        	users_portfolio.duration,
        	users_portfolio.id_type,
        	users_portfolio.id_time_type,
            users_portfolio.id_currency,


        	projects_type.name as workType,
        	c_title,
        	one,
        	many,
        	middle,
        	projects_type.title_many,
        	(users_portfolio.price * currency.c_abbr ) as orderPrice
        ');
        if ($portfolioId)
        {
            $this->db->where('users_portfolio.id',$portfolioId);
        }
        if ($userId){
            $this->db->where('id_user',$userId);
        }
        if (!$portfolioId){
            if ($shop){
                $this->db->where('shop',1);
            }else{
                $this->db->where('shop',0);
            }
        }

        $this->db->from('users_portfolio');
        $this->db->join('projects_type', 'projects_type.id = users_portfolio.id_type','left');
        $this->db->join('currency','currency.id=users_portfolio.id_currency','left');
        $this->db->join('projects_time_type','projects_time_type.id=users_portfolio.id_time_type','left');

        // get section for shop
        //print_r ($this->input->get());
        if ($this->input->get('page'))
        {
            $this->db->limit(SHOP_SEARCH_RESULTS, ($this->input->get('page')-1)*SHOP_SEARCH_RESULTS);
        }else{
            if ($page){
                $_GET['page'] = $page;
                $this->db->limit(SHOP_SEARCH_RESULTS, ($this->input->get('page')-1)*SHOP_SEARCH_RESULTS);
            }

        }

        if($this->input->get('query')){
            $this->db->like('title',$this->input->get('query'));
        }
        if($this->input->get('priceOrder')){
            $this->db->order_by('orderPrice',$this->input->get('priceOrder'));
        }else{ // section for user portfolio
            $this->db->order_by('projects_type.id','DESC');
            $this->db->order_by('users_portfolio.createTime','DESC');
        }
        if($this->input->get('workTypeId')){
            $this->db->where('id_type',$this->input->get('workTypeId'));
        }
        //searching in set of departments
        if ($this->input->get('DepartmentList')){
            $this->db->where ( "(select count(id_department)
                                from department_portfolio
                                where users_portfolio.id = department_portfolio.id_portfolio
                                and id_department in (".join(',',$this->input->get('DepartmentList')).") > 0 )
	        ");
        }
        // END shop search section
        $query =  $this->db->get();
        $this->load->model('Department');
        $portfolioList = $query->result();

        foreach ($portfolioList as $item){
            $item->departmentList = $this->Department->getPortfolioDepartments($item->workId);
        }
        return $portfolioList;
    }

    public function searchUser(){
        $this->db->from('users');
        $this->db->join('department_users','users.id = department_users.id_user','left');
        if ($this->input->post('id_department')){
            $this->db->where ('id_department',$this->input->post('id_department'));
        }
        if ($this->input->post('word')){
            $this->db->like ('firstName',$this->input->post('word'),'both');
            $this->db->or_like ('lastName',$this->input->post('word'),'both');
        }
        $this->db->group_by('id'); // if id_department doesn't take part in request
        $query = $this->db->get();
        return $query->result();
    }
    public function getSimplePortfolioList($userId,$portfolioId){
        $this->db->where('id_user',$userId);
        $this->db->where('.id',$portfolioId);
        $this->db->get('users_portfolio');
    }
    public function getPortfolioAmount($userId = false,$shop=false)
    {
        if ($shop){
            $this->db->where('shop',1);
        }
        if ($userId){
            $this->db->where('id_user',$userId);
        }
        $this->db->from('users_portfolio');
        $this->db->join('projects_type', 'projects_type.id = users_portfolio.id_type','left');

        $query =  $this->db->get();

        return $this->db->affected_rows();
    }
    public function addReview(){
        if ($this->input->post('id_review')){
            $this->db->where('id',$this->input->post('id_review'));
            $this->db->update('users_review',array(
                'comment'   =>  $this->input->post('comment'),
                'mark'      =>  $this->input->post('mark')
                                             ));
        }else{
            $this->db->insert('users_review',$this->input->post());
        }

    }
    public function addWork()
    {
        //print_r($_POST);
        $DepartmentList = $this->input->post('DepartmentList');
        unset($_POST['submit1']);
        unset($_POST['DepartmentList']);
        $this->load->model('Photos');
        $photo = $this->Photos->uploadPhoto('works');
        //print_r ($photo);
        if (!empty($photo['insertData']['PhotoLink'])){
            $_POST['picture'] = $photo['insertData']['PhotoLink'];
        }

        //print_r ($_FILES);
        //die();
        if (!empty($_FILES["workFile"]["name"])
            & ($_FILES["workFile"]["size"] < 1000000))
        {
            $fileName = md5(time()).$_FILES["workFile"]["name"];
            move_uploaded_file($_FILES["workFile"]["tmp_name"],
            "./import/work_files/" . $fileName);
            $_POST['workFile'] = $fileName;
        }
        $_POST['id_user'] = $this->session->userdata('UserID');

        if (!empty($_POST['id'])){
            $this->db->where('id',$_POST['id']);
            $workId = $_POST['id'];
            unset($_POST['id']);
            $this->db->update('users_portfolio',$this->input->post());

        }else{
            $this->db->insert('users_portfolio',$this->input->post());
            $workId = $this->db->insert_id();
        }



        foreach ($DepartmentList as $item) {
            $this->db->insert('department_portfolio',array('id_portfolio'=>$workId,'id_department'=>$item));
        }
    }
    public function updateWatches($userId)
    {
        if ($this->session->userdata('UserID') != $userId){
            $this->db->query("UPDATE users SET watches = watches+1 WHERE id = {$userId}");
        }
    }
    public function isAdmin($user){
        $user = $this->db->get_where('users',array('id'=>$user));
        if ($user->row()->admin){
            return true;
        }else{
            return false;
        }
    }
    public function isSuperAdmin($user){
        $user = $this->db->get_where('users',array('id'=>$user));
        if ($user->row()->system){
            return true;
        }else{
            return false;
        }
    }
    public function actionAdmin($userId,$mark){
        $this->db->where('id',$userId);
        $data = array('admin'=>$mark);
        $this->db->update('users',$data);
    }
    public function blockUser($userId,$mark){
        $this->db->where('id',$userId);
        $data = array('blocked'=>$mark);
        $this->db->update('users',$data);
    }
    public function checkBlocked($userId){
        $this->db->where('id',$userId);
        $query = $this->db->get('users');
        if ($query->row()->blocked){
            return true;
        }else{
            return false;
        }
    }
    public function setLastLogin($userId){
        $this->db->where('id',$userId);
        $data = array('lastLogin' => date('Y-m-d H:i:s'));
        $this->db->update('users',$data);
    }
    public function checkEmail($email){
        $this->db->get_where('users',array('email' => trim($email)));
        if ($this->db->affected_rows()>0 ){
            return true;
        }else{
            return false;
        }
    }
    public function recovery($email){
        $this->db->where('email' , $email);
        $recovery = md5($email.date('d-m-Y'));
        $this->db->update('users',array('recovery' => $recovery));
        mail ( $email , 'Free-write.ru - восстановление пароля',  "Для того что бы восстановить пароль пройдите по <a href='".base_url()."main/finishPasswordRecovery/{$recovery}"."'>ссылке</a>");
    }
    public function checkRecovery($recovery){
        $query = $this->db->get_where('users',array('recovery' => $recovery));
        if ($this->db->affected_rows()>0 ){
            $id =  $query->row()->id;
            $this->db->where('id' , $id);
            $this->db->update('users',array('recovery' => ''));
            return $id;
        }else{
            return false;
        }
    }
    public function updateAccount($data){
        $this->db->where('id',$this->session->userdata('UserID'));
        $this->db->update('users',$data);
    }
    public function getPromoCode($userId){
        $query = $this->db->get_where('users',array('id'=>$userId));
        if (!empty($query->row()->promo_code)){
            return $query->row()->promo_code;
        }else{
            require_once('PseudoCrypt.php');
            $code = PseudoCrypt::udihash($userId);
            $this->db->set('promo_code',$code);
            $this->db->where('id' , $userId);
            $this->db->update('users');
            return $code;
        }
    }
    public function setDiscountPersonal($id,$data){
        $this->db->set('discount_personal',$data['discount_personal']);
        $this->db->where('id',$id);
        $this->db->update('users');
    }
    public function setPersonalDiscount($userId){
        $this->db->query("update users set users.discount_personal = users.discount_total where id={$userId}");

    }

    public function getUserIdByPortfolioId($portfolioId){
        $query = $this->db->get_where('users_portfolio',array('id'=>$portfolioId));
        if ($this->db->affected_rows()){
            return $query->row()->id_user;
        }else{
            return false;
        }
    }

    /**
     * setting discount for referal programm
     * @param $promoCode
     * @param $userId
     */
    public function setUserReferal($promoCode,$userId){
        $query = $this->db->get_where('users',array('promoCode'=>$promoCode));
        $user = $query->row();

        $data = array(  'id_user_slave'  => $userId,
                        'id_user_master' => $user->id,
                        'discount_to'    => ($user->discount_total - $user->discount_personal), // % payment to master user
                        'discount_from'  => $user->discount_personal                          // personal slave user disocunt
                        );
        $this->db->insert('users_promo',$data);
    }
    /**
     * get info for transfering % to master user
     */
    public function getDiscountTransfer($userId){
        $this->db->where('id_user_slave',$userId);
        $query = $this->db->get('users_promo');
        return $query->row();
    }

    /**
     * get discount for current user
     * @param $userId
     * @return int
     */
    public function getDiscount($userId){
        $discount = $this->getUserExpertDiscount($userId);

        $this->db->where('id_user_slave',$userId);
        $query = $this->db->get('users_promo');
        if ($this->db->affected_rows()){
            $discount += $query->row()->discount_from;
        }
        return $discount;
    }
    /**
     * cut discount from payment
     */
    public function getDiscountPayment($userId,$price){
        $discount = $this->getDiscount($userId);
        if ($discount)
            $newPrice = $price - ($price/100)*$discount ;
        else
            $newPrice = $price;
        return $newPrice;
    }
    /**
     * get additional discount for expert users
     * @param $userId
     * @return int
     */
    public function getUserExpertDiscount($userId){
        $user = $this->getUser($userId);

        if ($user->expert == 1 && $user->type == 2){
            return 25;
        }else{
            return 0;
        }
    }
    /**
     * cheeck ability user to vote
     */
    public function checkUserToUserVoteAbility($userId){
        $data = array(
            'id_user_to'    => $userId,
            'id_user_from'  => $this->session->userdata('UserID')
        );
        $this->db->get_where('user_to_user_rating',$data);
        if ($this->db->affected_rows() > 0 ){
            return false;
        }else{
            return true;
        }

    }
    /**
     * set new mark for user
     */
    public function setUserToUserRating($userId,$mark){
        if ($mark != 1) $mark = -1;
        $data = array(
                    'id_user_to'    => $userId,
                    'id_user_from'  => $this->session->userdata('UserID'),
                    'mark'          => $mark
                );
        $this->db->insert('user_to_user_rating',$data);
    }
    /**
     * gettin alternative rating
     */
    public function getUserToUserRating($userId){
        $this->db->where('id_user_to',$userId);
        $this->db->select('sum(mark) as balance');
        $query = $this->db->get('user_to_user_rating');
        $balance = $query->row()->balance;
        if ($balance){
            return $balance;
        }else{
            return 0;
        }
    }

    public function deleteWork($workId){
        $this->db->where('id',$workId);
        $this->db->where('id_user',$this->session->userdata('UserID'));
        $this->db->delete('users_portfolio');
        if ($this->db->affected_rows()){
            $this->db->where('id_portfolio',$workId);
            $this->db->delete('department_portfolio');
        }
    }
}
