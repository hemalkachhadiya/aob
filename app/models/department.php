<?php
class Department extends CI_Model
{
    public function getAjaxDepartmentsAutocomplete($search=false)
    {
        if ($search){
            $this->db->like('name',$search,'both');
        }
        $this->db->order_by('name');
        $query = $this->db->get('department');
        if ($this->db->affected_rows()>0){
            return $query->result();
        }else{
            return false;
        }
    }
    public function getParentDepartments(){

    }
    public function getExternalUserDepartments($userId)
    {
        $this->db->from('department');
        $this->db->where('department.id not in (select id_department from department_users where  id_user ='.$userId.')');
        $query = $this->db->get();
        if ($this->db->affected_rows()>0){
            return $query->result();
        }else{
            return false;
        }
    }
    public function getPortfolioDepartments($portfolioId,$list = false,$limit = false)
    {
        if ($limit)
        {
            $this->db->limit($limit);
        }
        if ($list)
        {
            $this->db->where_in('id_department',$list);
        }
        $this->db->select('id_department,name');
        $this->db->from('department');
        $this->db->join('department_portfolio','department.id = department_portfolio.id_department' ,'left');
        $this->db->where('id_portfolio',$portfolioId);
        $query = $this->db->get();
        if ($this->db->affected_rows()>0){
            return $query->result();
        }else{
            return false;
        }
    }
    public function getProjectDepartments($portfolioId)
    {
        $this->db->select('id_department,name');
        $this->db->from('department');
        $this->db->join('department_projects','department.id = department_projects.id_department' ,'left');
        $this->db->where('id_project',$portfolioId);
        $query = $this->db->get();
        if ($this->db->affected_rows()>0){
            return $query->result();
        }else{
            return false;
        }
    }
    public function getCommonDepartments($table,$id)
        {
            $departmentField = array (
                'department_portfolio'  => 'id_portfolio',
                'department_users'      => 'id_user',
                'department_projects'   => 'id_project'
            );
            $field = $departmentField[$table];
            $this->db->select('id_department,name');
            $this->db->from('department');
            $this->db->join($table,"department.id = $table.id_department" ,'left');
            $this->db->where($field,$id);
            $query = $this->db->get();
            if ($this->db->affected_rows()>0){
                return $query->result();
            }else{
                return false;
            }
        }
    public function getUserDepartments($userId,$type ,$list = false,$limit = false)
    {
        if ($limit)
        {
            $this->db->limit($limit);
        }
        if ($list)
        {
            $this->db->where_in('id_department',$list);
        }
        $this->db->select('id_department,name');
        $this->db->from('department');
        $this->db->join('department_users','department.id = department_users.id_department' ,'left');
        $this->db->where('id_user',$userId);
        if ($type){
            $this->db->where('user_department_type',$type);
        }

        $query = $this->db->get();
        if ($this->db->affected_rows()>0){
            return $query->result();
        }else{
            return false;
        }
    }
    public function getDepartmentsPlainArray(){
        $list = array ();
        $departments = $this->getDepartments();
        foreach ($departments as $item){
            $list[$item->id] = $item->name;
        }
        return $list;
    }
    public function getDepartments(){
        $this->db->order_by('name');
        $query = $this->db->get('department');
        if ($this->db->affected_rows()>0){
            return $query->result();
        }else{
            return false;
        }
    }
    public function getAjaxDepartments($idParent){
        $this->db->where('id_parent',$idParent);
        $this->db->order_by('name');
        if ($this->input->post('search')){
            $this->db->like('name',$this->input->post('search'),'both');
        }
        $query = $this->db->get('department');
        if ($this->db->affected_rows()>0){
            return $query->result();
        }else{
            return false;
        }
    }
    public function getAjaxParentDepartments(){
        $this->db->order_by('name');
        if ($this->input->post('search')){
            $this->db->like('name',$this->input->post('search'),'both');
        }
        $query = $this->db->get('department_parent');
        if ($this->db->affected_rows()>0){
            return $query->result();
        }else{
            return false;
        }
    }
    public function set($DepartmentList,$type){
        foreach($DepartmentList as $item){
            $data = array(
                'user_department_type'  =>  $type,
                'id_user'               => $this->session->userdata('UserID'),
                'id_department'         => $item
            );
            $this->db->insert('department_users',$data);
        }
        $list = $this->getUserDepartments($this->session->userdata('UserID'),$type,$DepartmentList);
        return $list;
    }
    public function delete($departmentId,$userId){
        $this->db->where('id_department',$departmentId);
        $this->db->where('id_user',$userId);
        $this->db->delete('department_users');
    }
    public function editUserDepartmentList($departmentList,$userId,$type){
        $this->db->where('id_user',$userId);
        $this->db->where('user_department_type',$type);
        $this->db->delete('department_users');
        if (!empty($departmentList)){
            foreach($departmentList as $item){
                $data = array(
                    'id_user'   => $userId,
                    'user_department_type' => $type,
                    'id_department' => $item
                );
                $this->db->insert('department_users',$data);
            }
        }
    }
    public function getName($departmentId){
        $query = $this->db->get_where('department',array('id'=>$departmentId));
        return $query->row()->name;
    }
    public function getDepartment($departmentId,$table = 'department'){
            $query = $this->db->get_where($table,array('id'=>$departmentId));
            return $query->row();
        }

    public function getBranchName($departmentId){
        $tmp = $this->getDepartment($departmentId);
        $data['child'] = $tmp->name;
        $data['parent'] = $this->getDepartment($tmp->id_parent,'department_parent')->name;
        return $data;
    }
    public function getAdminDepartment(){
        $list = $this->getAjaxParentDepartments();
        foreach ($list as $item){
            $item->childList = $this->getAjaxDepartments($item->id);
        }
        return $list;
    }
    public function setDepartment($id,$name,$table){
        $this->db->where('id',$id);
        $this->db->update($table,array('name' => $name));
    }
    public function addDepartment($data,$table){
        $this->db->insert($table,$data);
    }

    
    public function deleteDepartments($table,$id_department){
        if ($table == 'department'){
            $this->deleteDepartment($id_department);
        }
        if ($table == 'department_parent'){
            $list = $this->getAjaxDepartments($id_department);  // getting child departments

            $this->db->where('id',$id_department);              // deleting parent department
            $this->db->delete($table);

            foreach($list as $item){
                $this->deleteDepartment($item->id);
            }
        }

    }
    public function deleteDepartment($id_department){
        $this->db->where('id_department',$id_department);
        $tables = array('department_portfolio', 'department_users', 'department_projects');
        $this->db->delete($tables);

        $this->db->where('id',$id_department);
        $this->db->delete('department');

    }
}