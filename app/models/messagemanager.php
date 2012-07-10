<?php
class MessageManager extends CI_Model
{
    /**
     * retrieve group of messages by chain
     * @param  $chainId
     * @param bool $limit
     * @return
     */
    public function getChain($chainId,$limit = false)
    {
       $page = 1;
       if ($this->input->get('page')){
           $page = $this->input->get('page');
       }

       $preLimit = ($page-1)*SEARCH_RESULTS;
       $postLimit = SEARCH_RESULTS;
       if ($limit){
           $limit = "limit $preLimit,$postLimit";
       }else{
           $limit = "";
       }

       $sqlQuery = "
            select id_to as _To,id_from as _From,subject,body,file,createTime,unread,firstName_To,lastName_To,picture_To,nickname_To,
            users.firstName as firstName_From,users.lastName as lastName_From,users.picture as picture_From,
            users.nickname as nickname_From,chain,mailId,file_name,expert_To,type_To,users.expert as expert_From,users.type as type_From
             from (
                select
                        mail.id as mailId,id_to,id_from,subject,body,file,createTime,unread,
                        users.firstName as firstName_To, users.lastName as lastName_To, users.picture as picture_To,chain,
                        users.nickname as nickname_To,file_name,users.expert as expert_To,users.type as type_To
                from
                    mail
                left join
                    users
                on
                    users.id = mail.id_to
                where
                    chain = $chainId
                and
                    mail.id not in (select id_mail from mail_deleted where id_user = {$this->session->userdata('UserID')})
                order by createTime DESC

                $limit
                ) as firstPart

            LEFT JOIN
                users
            on
                users.id = firstPart.id_from
        ";
        $query = $this->db->query($sqlQuery);
        $amount = $this->db->affected_rows();
        //var_dump ($amount);
        $chainList = $query->result();
        if (!$limit) {
            return $amount;
        }else{
            return $chainList;
        }

    }
    public function getMessageAmountInChain($chain)
    {
        $query = $this->db->get_where('mail',array('chain' => $chain));
        return $this->db->affected_rows();
    }
    public function getChainsList($userId,$limit = false)
    {
       $page = 1;
       if ($this->input->get('page')){
           $page = $this->input->get('page');
       }

       $preLimit = ($page-1)*SEARCH_RESULTS;
       $postLimit = SEARCH_RESULTS;
       if ($limit){
           $limit = "limit $preLimit,$postLimit";
       }else{
           $limit = "";
       }
        $sqlQuery = "
            select id_to as _To,id_from as _From,subject,body,file,createTime,unread,firstName_To,lastName_To,picture_To,nickname_To,
            users.firstName as firstName_From,users.lastName as lastName_From,users.picture as picture_From,users.nickname as nickname_From,chain,
            expert_To,type_To,users.expert as expert_From,users.type as type_From
            from (
                select
                        id_to,id_from,subject,body,file,createTime,unread,users.firstName as firstName_To, users.lastName as lastName_To, users.picture as picture_To,chain,
                        users.nickname as nickname_To,users.expert as expert_To,users.type as type_To
                from
                    mail
                left join
                    users
                on
                    users.id = mail.id_to
                where
                    (id_to = $userId
                or
                    id_from = $userId)
                and
                    mail.id not in (select id_mail from mail_deleted where id_user = {$this->session->userdata('UserID')})

                    order by createTime DESC
                ) as firstPart

            LEFT JOIN
                users
            on
                users.id = firstPart.id_from
            group  by firstPart.chain
            order by firstPart.createTime DESC


        ";
        $query = $this->db->query($sqlQuery);
        $chainList = $query->result();

        $amount = $this->db->affected_rows();
        //var_dump ($amount);
        if (!$limit) {
            return $amount;
        }else{
            if ($chainList) {
                foreach ($chainList as $item){
                    $item->messageAmountInChain = $this->getMessageAmountInChain($item->chain);
                }
                return $chainList;
            }

        }

    }
    public function setChain()
    {
        $this->db->select_max('chain');
        $query = $this->db->get('mail');
        if (!empty($query->row()->chain)){
            return $query->row()->chain;
        }else{
            return 1;
        }
    }
    public function isChainExist($id)
    {
        $this->db->get('mail',array('chain' => $id));
        if ($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }
    public function getUnreadAmount($userId)
    {
        $this->db->where('id_to',$userId);
        $this->db->where('unread',1);
        $this->db->get('mail');
        return $this->db->affected_rows();
    }
    public function create ($data){

        if (!empty($_FILES["workFile"]["name"])
            & ($_FILES["workFile"]["size"] < 1000000))
        {
            $fileName = md5(time()).$_FILES["workFile"]["name"];
            move_uploaded_file($_FILES["workFile"]["tmp_name"],
            "./import/mail/" . $fileName);
            $data['file'] = $fileName;
            $data['file_name'] = $_FILES["workFile"]["name"];
        }

        $this->db->insert('mail',$data);
    }
    public function setChainMailRead($chainId)
    {
        $this->db->where('chain',$chainId);
        $this->db->where('id_to',$this->session->userdata('UserID'));
        $this->db->update('mail',array('unread'=>0));
    }
    public function deleteMessage($id){
        $data = array(
            'id_mail'   => $id,
            'id_user'   => $this->session->userdata('UserID')
        );
        $this->db->insert('mail_deleted',$data);
    }
    public function getChainByUsers($fUser,$sUser){
        $this->db->select('chain');
        $this->db->where("(id_to=$fUser and id_from=$sUser)");
        $this->db->or_where("(id_from=$fUser and id_to=$sUser)");
        $this->db->group_by('chain');
        $query = $this->db->get('mail');
        if ($this->db->affected_rows()){
            return $query->row()->chain;
        }else{
            return false;
        }


    }
}