<?php
/**
 * saving feedback data for acccessing it in admin panel
 */
 class Common extends CI_Model{
    public function savefeedback($data){
        $this->db->insert('feedback',$data);
    }
}
 
