<?php
include_once ('./mig-pay/mig-pay.inc.php') ;
class MigPay extends CI_Controller
{
    private $MPPI;
    public function __construct(){
        parent::__construct();
        $this->load->model('MigPayManager');
        //$this->MPPI = new MP_Merchant();
        $this->MPPI = new MP_Merchant(array(
            //'site_result_url'		=> '/MP_result.php',
            'site_success_url'	    => '/migpay/index',
            'site_cancel_url'		=> '/migpay/index',
            'on_payment_success'	=> array ($this,'Payment_Success_Handler'),
            'on_payment_cancel' 	=> array ($this,'Payment_Cancel_Handler')
        ));
    }
    public function index(){
        $this->MPPI->HandleResultRequest();
        //redirect();
    }
    public function getBalance(){
        $result = $this->MPPI->request_BalanceInfo(array(
			'ECURRENCY' => 'MIGRUR',
			'CURRENCY'  => 'RUR'
		));
        var_dump ($result);
    }
    public function createOrder()
    {
        $order_nr = $this->MigPayManager->setOrderNr(5,$this->input->post('id_system'));
        $this->MPPI->Open_Order_Page(array(
            'order_nr'      => $order_nr,
            'amount'        => $this->input->post('amount'),
            'currency'      =>'RUR',
            'description'   =>'description'
        ));
    }

    function Payment_Success_Handler(
         $order_nr, $amount, $currency, $description, $info, $params )
    {
        $this->MigPayManager->setOrderStatus($order_nr,2);
        redirect();
    }

    function Payment_Cancel_Handler(
     $order_nr, $amount, $currency, $description, $info, $params )
    {
        $this->MigPayManager->setOrderStatus($order_nr,3);
        redirect();
    }
}