    <? if (!$this->authmanager->isLogged()) { ?>
        <? $this->load->view('content/popups/login'); ?>
    <? } else {  ?>
        <? if (!$userData->finished) { ?>}
            <!-- <div id="popup-bg"  ></div> -->
            <? $this->load->view('content/popups/login_finished'); ?>
        <? } ?>
        <? $this->load->view('content/popups/ask_question'); ?>
        <? $this->load->view('content/popups/update_account'); ?>
        <? $this->load->view('content/popups/payment_dialog'); ?>
        <? $this->load->view('content/popups/statistics'); ?>
        <? $this->load->view('content/popups/information'); ?>
    <? } ?>
    <? $this->load->view('content/popups/search_freelancer'); ?>
    <? $this->load->view('content/popups/contact_us'); ?>
