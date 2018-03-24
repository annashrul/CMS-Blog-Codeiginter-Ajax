<?php
defined('BASEPATH') OR exit('No direct script access allowed');

  class MY_Model extends CI_Model{
    protected $_table_name; // for name of table
  	protected $_order_by; // order by {{name_column}}
  	protected $_order_by_type = 'ASC'; // asc or desc
  	protected $_primary_filter = 'intval';
    protected $_primary_key; // primary key
    protected $_post_type; // post type

      function __construct(){
        parent::__construct();
      }

      public function insert($data, $batch = false){
          if ($batch == TRUE) {
             $this->db->insert_batch('{PRE}'.$this->_table_name, $data);
             //baca: $this->db->insert_batch('tbl_user',$data);
          }else {
            $this->db->set($data);
            $this->db->insert('{PRE}'.$this->_table_name);
            // mengambil id terbaru dari setiap insert data.
            $id = $this->db->insert_id();
            // mengembalikan $id insert
            return $id;
          }
      }

      public function update($data, $where=array()){
        $this->db->set($data);
        $this->db->where($where);
        return $this->db->update('{PRE}'.$this->_table_name);
      }

      public function get($id = NULL, $single = FALSE){

          if ($id != NULL) {
             $filter = $this->_primary_filter;
             $id     = $filter($id); //using intval to confirm $id is integer
             $this->db->where($this->_primary_key,$id);
             $method = 'row';
          }else if($single == TRUE) {
            $method = 'row';
          }else {
            $method = 'result';
          }

          // if order by type is filled
          if ($this->_order_by_type) {
              $this->db->order_by($this->_order_by, $this->_order_by_type);
          }else {
              $this->db->order_by($this->_order_by);
          }

          return $this->db->get('{PRE}'.$this->_table_name)->$method();

      }

      public function get_by($where = NULL, $limit = NULL, $offset = NULL, $single = FALSE, $select = NULL, $like = NULL){
          if ($select) {
            $this->db->select($select);
          }

          if ($where) {
            $this->db->where($where);
          }

          if (($limit) && ($offset)) {
            $this->db->limit($limit,$offset);
          }elseif ($limit) {
            $this->db->limit($limit);
					}

					if($like){
						$this->db->like($like);

					}


          return $this->get(NULL, $single);
      }

      public function delete($id){
        $filter = $this->_primary_filter;
        $id = $filter($id);

        if (!$id) {
          return FALSE;
        }

        $this->db->where($this->_primary_key,$id);
        $this->db->limit(1);
        return $this->db->delete('{PRE}'.$this->_table_name);
      }

      public function delete_by($where = NULL){
        if ($where) {
          $this->db->where($where);
        }

        $this->db->delete('{PRE}'.$this->_table_name);
      }

      public function count($where=NULL){
        if (!Empty($this->_post_type)) {
          $where['post_type'] = $this->_post_type;
        }
        if($where){
    			$this->db->where($where);
    		}

    		$this->db->from('{PRE}'.$this->_table_name);
    		return $this->db->count_all_results();
      }


  }
