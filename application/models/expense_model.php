<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Anik
 * Date: 10/14/13
 * Time: 9:18 AM
 * To change this template use File | Settings | File Templates.
 */

class Expense_model extends CI_Model {
    function __construct(){
        parent::__construct();
    }


    public function create(){
        $dtime = new DateTime();
        $data = $this->input->post();
        $data['user_id'] = $this->session->userdata('user_id');
        //$data['created_at'] = $dtime->format('Y-m-d H:i:s');
        $this->db->insert('expenses' , $data);
    }

    public function get($select = '*' , $where = array() , $join = array()){
        $this->db->select($select);
        $this->db->where($where);
        $this->db->from('expenses');
        $query = $this->db->get();
        return $query->result();
    }


    public function update($expense_id) {
        $this->db->where('id', $expense_id);
        $this->db->update('expenses', $this->input->post());
    }

    public function delete($expense_id) {
        $this->db->delete('expenses', array('id' => $expense_id));
    }



    public function get_all_expense(){
        $sql = "SELECT expense.id as id, expense_type.reason as reason, showroom.name as showroom_name, expense.amount as amount, expense.created_at as date , expense.explanation FROM `expense` 
join expense_type on expense_type.id = expense.expense_type_id
join showroom on showroom.id = expense.showroom_id order by expense.id desc";
        $query = $this->db->query($sql);
        return $query->result();
    }



    public function get_expense_single_day() {
        $date = $this->input->get("select_date");
        $showroom_id = $this->input->get("showroom_id");

        $sql = "SELECT e.*, et.reason FROM `expenses` e
                join expense_type et on et.id = e.expense_type_id
                where
                  date_format(e.created_at, '%m/%d/%Y') = '$date'
                  and e.showroom_id = $showroom_id";
        $query = $this->db->query($sql);
        return $query->result();
    }


    public function get_expenses() {
        //$date = $this->input->get("select_date");
        $from_date = $this->input->get("from_date");
        $to_date = $this->input->get("to_date");

        $showroom_id = $this->input->get("showroom_id");
        $expense_type_id = $this->input->get("expense_type_id");
        $showroom_where = "e.showroom_id = $showroom_id and";
        if($showroom_id == 'all') {
            $showroom_where = '';
        }

        $expense_type_where = "e.expense_type_id = $expense_type_id and ";
        if($expense_type_id == 'all') {
            $expense_type_where = '';
        }

        $sql = "SELECT e.*, sr.name as showroom, u.username, et.reason as type FROM `expenses` e
                join showroom sr on sr.id = e.showroom_id
                join users u on u.id = e.user_id
                join expense_type et on et.id = e.expense_type_id
                where
                  $showroom_where
                  $expense_type_where
                  date_format(e.created_at, '%m/%d/%Y') between '$from_date' and '$to_date'";
        $query = $this->db->query($sql);
        return $query->result();
    }


}