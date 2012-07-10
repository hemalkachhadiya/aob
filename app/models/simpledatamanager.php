<?php
class SimpleDataManager extends CI_Model{
    public function getList($table='news')
    {
        if ($this->input->get('page')){
            $page = $this->input->get('page');
        }else{
            $page = 1;
        }
        $this->db->order_by('createTime','DESC');
        $this->db->limit(SEARCH_RESULTS, ($page-1)*SEARCH_RESULTS);
        $query = $this->db->get($table);
        $projectList = $query->result();
        return $projectList;
    }
    public function getListAmount($table='news')
    {
        $query = $this->db->get($table);
        return $this->db->affected_rows();
    }
    public function getItem($id,$table='news'){
        $query = $this->db->get_where($table,array('id'=>$id));
        return $query->row();
    }
    public function createNewsItem($table='news'){
        if ($this->input->post('id')){
            $this->db->where('id',$this->input->post('id'));
            unset($_POST['id']);
            $this->db->update($table,$this->input->post());
        }else{
            $this->db->insert($table,$this->input->post());
        }

    }
    public function createItem($table){
        $this->db->insert($table,$this->input->post());
    }
    public function updateItem($id,$data){
        if (empty($data['visible'])){
            $data['visible'] = 0;
        }
        $this->db->where('id',$id);
        $this->db->update('questions',$data);
    }
    public function getQuestionList($page,$limit = 3,$public = false){
        $this->db->select(
           'questions.id as questionId,
            answerWho,
            firstName,
            lastName,
            id_user,
            question,
            answer,
            id_user,
            createTime,
            nickname,
            visible,
            picture'
        );
        $this->db->order_by('createTime','DESC');
        $this->db->from('questions');
        $this->db->join('users','users.id = questions.id_user','left');
        if ($public){
            $this->db->where('visible',1);
        }

        if ($limit){
            $this->db->limit($limit, ($page-1)*$limit);
        }
        $query = $this->db->get();
        $list = $query->result();
        if ($limit){
            return $list;
        }else{
            return $this->db->affected_rows();
        }

    }
    public function delete($id,$table='news'){
        $this->db->where('id',$id);
        $this->db->delete($table);
    }
    public function search($limit = true){
        $search  = $this->input->get('search');
        //$search  =  'test';
        if ($limit) {
            if ($this->input->get('page')){
                $page = $this->input->get('page');
            }else{
                $page = 1;
            }
            $limit = "limit ".(($page-1)*SEARCH_RESULTS).",".SEARCH_RESULTS;
        }
        $query = $this->db->query("
            select *
            from
            (
                select
                    id as itemId,
                    title,
                    description,
                    id_user,
                    '/profile/portfolio/' as link,
                    'Работы'    as template,
                    createTime,
                    'department_portfolio' as departmentTable
                from users_portfolio
                where users_portfolio.shop = 0
                union

                select
                    id as itemId,
                    title,
                    description,
                    id_user,
                    '/profile/portfolio/' as link,
                    'Магазин'    as template,
                    createTime,
                    'department_portfolio' as departmentTable
                from users_portfolio
                where users_portfolio.shop = 1
                union
                select
                    id as itemId,
                    title,
                    description,
                    id_user,
                    '/project/'  as link,
                    'Проекты'    as template,
                    createTime,
                    'department_projects' as departmentTable
                from projects
                union
                select
                    id as itemId,
                    concat(users.firstName,' ',users.lastName) as title,
                    description,
                    id as id_user,
                    '/user/'  as link,
                    'Пользователи'    as template,
                    createDate as createTime,
                    'department_users' as departmentTable

                from users
            ) as collection
            where
              collection.description like '%{$search}%'
            or
              collection.title like '%{$search}%'
            $limit
        ");


        if(!$limit) {
            return $this->db->affected_rows();
        } else{


            $list = $query->result();

            $this->load->model('Department');
            if (!empty($list)){
                foreach ($list as $item){
                    $item->departmentList = $this->Department->getCommonDepartments($item->departmentTable,$item->itemId);
                }
            }
            return $list;
        }


    }
}
