<?php
class Cron extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('CronManager');
    }
    public function dropExpired(){
        $this->CronManager->dropExpert();
        $this->CronManager->dropExpert();
        $this->CronManager->dropExpert();
    }
}
 
