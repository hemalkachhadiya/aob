<?php
class SlaveModel extends CI_Model{
    public function isSubscriberEmail($email){
        $this->db->where('email',$email);
        $query = $this->db->get('subscriber_list');
        if ($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    public function addSubscriberEmail($email){
        $this->db->insert('subscriber_list', array(
            'email' => $email
        ));
    }
}