<?php
/**
 * payment_catalog
 */
class Payment extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Users');
        $this->load->model('PaymentManager');
    }
    /**
     * main ajax method for buying items/services
     * @return string
     */
    public function buy(){
        //var_dump ($this->input->post());
        $balance = $this->Users->getBalance($this->session->userdata('UserID'));
        $data = array(
            'status'    => false,
            'message'   => 'системная ошибка'
        );
        //$conditions = true && $this->input->post('LocalPaymentType') && $this->input->post('LocalPaymentTarget');
        // buying items in the shop
        if ($this->input->post('LocalPaymentType') == 'shop'){
            $payment = $this->Users->getPortfolioList(false,false,$this->input->post('LocalPaymentTarget'),true);
            $payment = $payment[0];
            $price = $payment->orderPrice;
            $method = "sendShopLink";
            $data['portfolioItem'] = $payment;
        }
        //system payments
        if ($this->input->post('LocalPaymentType') == 'system'){
            //getting price for one day or some regular offer
            $payment = $this->PaymentManager->getPaymentFromCatalog($this->input->post('LocalPaymentTarget'),$this->input->post('service'));
            //print_r($payment);
            $operationPrice = $payment->price;
            $price = $this->Users->getDiscountPayment($this->session->userdata('UserID'),$payment->price);
            $method = "set_".$this->input->post('LocalPaymentTarget');
            $conditions = $payment->duration ;
            $data['duration'] = $payment->duration ; // system user
        }
        $data ['userId'] = $this->session->userdata('UserID');


        if ($this->checkPaymentAbility($balance,$price)){

            $data = $this->PaymentManager->$method($data);
            if ($data['status']){
                $this->PaymentManager->setPayment($price);
                if ($this->input->post('LocalPaymentType') == 'system'){
                    $discountPayment = $this->Users->getDiscountTransfer($this->session->userdata('UserID'));
                    if (!empty($discountPayment->discount_to )){
                        $percentage = ($operationPrice/100)*$discountPayment->discount_to ;
                        $this->PaymentManager->transferPayment($percentage,$discountPayment->id_user_master);
                    }
                }
                if ($this->input->post('LocalPaymentType') == 'shop'){
                    $this->PaymentManager->transferPayment($price,$payment->id_user);
                }
            }else{
                $data['message'] = 'Оплата не состоялась.';
            }

        }else{
            $data['message'] = 'У Вас недостаточно средств на счету.';
        }

        echo json_encode($data);
    }
    public function checkPaymentAbility($balance,$payment){
        if ($payment <= $balance){
            return true;
        }else{
            return false;
        }
    }
    public function getTemplate($template){

        if ($this->input->post()){
            $data = $this->input->post();
            $data['PaymentOptions'] = $this->PaymentManager->getPaymentOptions($template);

            $this->load->model('ProjectManager');
            $data['ProjectList']    = $this->ProjectManager->getList($this->session->userdata('UserID'));
            $data['PortfolioList']  = $this->Users->getPortfolioList($this->session->userdata('UserID'),false,false,true);
        }

        $this->load->model('Users');
        $data['discount'] = $this->Users->getDiscount($this->session->userdata('UserID'));
        echo $this->load->view('content/payments/'.$template,$data);
    }
    public function getPaymentTexts(){
        $data    = $this->PaymentManager->getPaymentTemplate($this->input->post('template'));
        echo json_encode($data);
    }
    public function test(){
        $data = array(
            'duration'  => 15+2,
            'userId'    => 13
        );
        $this->PaymentManager->check_user_place_date($data);
    }
}
