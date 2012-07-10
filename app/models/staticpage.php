<?php
class StaticPage extends CI_Model
{
    public function getPage($name)
    {
        $query = $this->db->get_where('static_page',array('name'=>$name));
        return $query->row();
    }
    public function getPageById($id)
    {
        $query = $this->db->get_where('static_page',array('id'=>$id));
        return $query->row();
    }
    public function editStaticPage(){
        if ($this->input->post('pageId')){
            $this->db->where('id',$this->input->post('pageId'));
            unset($_POST[ 'pageId' ]);
            $this->db->update('static_page',$this->input->post());
        }
    }
    public function setContactUs($data){
        $this->db->insert('contact_us',$data);
    }
    public function getContactUs($limit=false){
        if ($this->input->get('page')){
            $page = $this->input->get('page');
        }else{
            $page = 1;
        }
        $this->db->order_by('createTime','DESC');
        if ($limit){
            $this->db->limit(SEARCH_RESULTS, ($page-1)*SEARCH_RESULTS);
        }

        $query = $this->db->get('contact_us');
        $projectList = $query->result();
        if ($limit){
            return $projectList;
        }else{
            return $this->db->affected_rows();
        }
    }

}