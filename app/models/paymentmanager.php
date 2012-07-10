<?php
class PaymentManager extends CI_Model{
    private $returnData = array(
        'status'    => true,
        'message'   => 'Вы успешно купили '
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('Users');
    }
    /**
     * get system payment from database
     * @param  $name
     * @return bool
     */
    public function getPaymentFromCatalog($name,$id = false){
        $data = array('name'=>$name);
        if ($id){
            $data['id'] = $id;
        }
        $this->db->where($data);
        $query = $this->db->get('payment_catalog');
        $payment = $query->row();
        if ($payment->type == PAYMENT_TYPE_RELATIVE ){ // if price depends on day amount
            $payment->price = $payment->price*$this->input->post('duration');
            $payment->duration = $this->input->post('duration');
        }

        if ($this->db->affected_rows()>0){
            return $payment;
        }else{
            return false;
        }
    }

    public function setPayment($price){
        $price =  -$price;
        $data = array(  'id_user'   => $this->session->userdata('UserID'),
                        'mig_sum'   => $price,
                        'status'    => 2,
                        'id_user_owner'=>$this->session->userdata('UserID')
                     );
        $this->db->insert('users_payment',$data);
        return true;
    }
    public function transferPayment($price,$userId){
        $data = array(  'id_user'   => $userId,
                        'mig_sum'   => $price,
                        'status'    => 2,
                        'id_user_owner'=>$this->session->userdata('UserID')
                    );
        $this->db->insert('users_payment',$data);
        return true;
    }


    /**
     * setting position in the carrousel
     * @param  $userId
     * @return void
     */
    public function set_carrousel_place($data){
        //delete previous user position
        $this->db->where('id_user',$data['userId']);
        $this->db->delete('users_carrousel');
        $this->db->insert('users_carrousel',array('id_user' => $this->session->userdata('UserID'),
                                                  'carrousel_comment' => $this->input->post('carrousel_comment')
                                                  ));
        $this->returnData['message'] = $this->returnData['message'].'Платное место в ленте';
        return $this->returnData;
    }
    /**
     * setting expert account
     * @param  $data
     * @return void
     */
    public function set_expert($data){
        $this->db->where('id',$data['userId']);
        $this->db->set('expert_expire',"ADDDATE(NOW(), INTERVAL {$data['duration']} DAY)",FALSE);
        $this->db->set('expert',1);
        $this->db->update('users');
        $this->returnData['message'] = $this->returnData['message'].'Аккаунт «Эксперт»';
        return $this->returnData;
    }
    public function set_project_place($data){
        $this->db->where('id_user',$data['userId']);
        $this->db->where('id',$this->input->post('id_project'));
        $this->db->set('projectTop',"ADDDATE(NOW(), INTERVAL {$data['duration']} DAY)",FALSE);
        $this->db->update('projects');
        $this->returnData['message'] = $this->returnData['message'].'Проект в топ';
        return $this->returnData;
    }
    /**
     * move project to top by date
     * @param $data
     * @return array
     */
    public function set_project_top_place($data){
        $this->db->where('id_user',$data['userId']);
        $this->db->where('id',$this->input->post('id_project'));
        $this->db->set('createTime',"NOW()",FALSE);
        $this->db->update('projects');
        $this->returnData['message'] = $this->returnData['message'].'Проект в топ';
        return $this->returnData;
    }
    public function set_shop_place($data){


        $this->db->where('id_user',$data['userId']);
        $this->db->where('id',$this->input->post('id_item'));
        $this->db->set('createTime',"NOW()",FALSE);
        $this->db->update('users_portfolio');
        $this->returnData['message'] = $this->returnData['message'].'Работа в топ';
        return $this->returnData;
    }
    public function set_user_place($data){
        $topUser = $this->check_user_place_date($data);

        $this->db->where('id',$data['userId']);
        if ($topUser){
            $this->db->set('topUser',"ADDDATE(NOW(), INTERVAL {$data['duration']} DAY)",FALSE);
        }else{ // fork for accumulating dates
            $this->db->set('topUser',"ADDDATE(topUser, INTERVAL {$data['duration']} DAY)",FALSE);
        }

        $this->db->set('topUser_comment',$this->input->post('topUser_comment'));
        $this->db->update('users');
        $this->returnData['message'] = $this->returnData['message'].'Платное место в рейтинге';
        return $this->returnData;
    }
    //todo cron job for setting expired dates to default 
    public function check_user_place_date($data){
        $this->db->where('id',$data['userId']);
        $query = $this->db->get('users');
        $topUser = $query->row()->topUser;
        if ('0000-00-00 00:00:00' == $topUser){
            return true;
        }else{
            return false;
        }


    }
    /**
     * sending link of portfolio item to the user
     * @param  $data
     * @return void
     */
    public function sendShopLink($data){
        $user = $this->Users->getUser($data['userId']);
        $to = $user->email;
        $subject = "Покупка работы \"{$data['portfolioItem']->title}\" ";
        $link = base_url().'import\work_files\\'.$data['portfolioItem']->workFile;
        $message = "Ccылка на скачивание работы {$link}";
        if (mail ( $to , $subject , $message))
        {
            $this->returnData['message']= 'Ссылка отправлена Вам на почту';
            $this->returnData['status'] = true;
        }else{
            $this->returnData['status'] = false;
        }
        $this->returnData['message'] = $this->returnData['message'].$data['portfolioItem']->title;
        return $this->returnData;
    }
    public function getPaymentOptions($template){
        $query = $this->db->get_where('payment_catalog',array('name'=>$template));
        return $query->result ();
    }
    public function getPaymentTemplate($template){
        $user = $this->Users->getUser($this->session->userdata('UserID'));
        $query = $this->db->get_where('payment_template',array('template'=>$template,'user_type'=>$user->type));
        return $query->row ();
    }
    public function getCyrillicMonth($num){
        $months = array(0,'янв','фев','март','апр','май','июнь','июль','авн','сен','окт','ноя','дек');
        return $months[$num];
    }
    public function getMonthStatistics(){
        $this->db->select('
            (select
                sum(mig_sum)
            from
                users_payment
            where month( users_payment.creatTime) = month( main.creatTime)
                and
            users_payment.id_user <> users_payment.id_user_owner
            ) as PaymentSum,
            month( creatTime) as PaymentMonth
            ');
        $this->db->from ('users_payment as main');
        $this->db->group_by('PaymentMonth');
        $query = $this->db->get();
        $list = $query->result();
        //print_r ($list)    ;
        $result = array();
        foreach ($list as $item){
            $result[] = array($item->PaymentSum,$this->getCyrillicMonth($item->PaymentMonth));
        }

        return $result;

    }

}