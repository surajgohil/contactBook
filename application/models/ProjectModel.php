<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ProjectModel extends CI_Model {

    public function insertData($tableName, $data){
        return $this->db->insert($tableName, $data);
    }

    public function selectData($isSingle = 0, $tableName, $column = '*', $where = [], $orderBy = [], $limit = null, $offset = null, $likeValue = '') {

        $this->db->select($column)->from($tableName);

        if (!empty($where)) {
            $this->db->where($where);
        }

        if(!empty($likeValue)){
            $this->db->group_start();
            $this->db->like('first_name', $likeValue);
            $this->db->or_like('last_name', $likeValue);
            $this->db->or_like('email', $likeValue);
            $this->db->or_like('number', $likeValue);
            $this->db->group_end();
        }

        if (!empty($orderBy)) {
            $this->db->order_by($orderBy[0], $orderBy[1]);
        }

        if (!is_null($limit)) {
            $this->db->limit($limit, $offset);
        }

        if($isSingle == 1){
            $data = $this->db->get()->row_array();
        }else{
            $data = $this->db->get()->result_array();
        }

        return $data;
    }

    public function deleteData($tableName, $where){
        return $this->db->delete($tableName, $where);
    }

    public function updateData($tableName, $where, $data){

        $this->db->where($where);
        return $this->db->update($tableName, $data);
    }

    function countData($tableName, $columnName = '*', $where = []){
    
        $this->db->select($columnName)->from($tableName);

        if(!empty($where)){
            $this->db->where($where);
        }

        return $this->db->count_all_results();
    }
}

?>