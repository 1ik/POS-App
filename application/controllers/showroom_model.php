<?php

/**

 * Created by JetBrains PhpStorm.

 * User: Anik

 * Date: 10/12/13

 * Time: 4:03 PM

 * To change this template use File | Settings | File Templates.

 */



class Showroom_model extends CI_Model {

    function __construct(){

        parent::__construct();

    }



    public function get($select = '*' , $where = array()){

        $this->db->select($select);

        $this->db->where($where);

        $query = $this->db->get('showroom');

        return $query->result();

    }



    public function update($id){

        $this->db->where(array('id' => $id));

        $data['name'] = $this->input->post('name');

        $data['location'] = $this->input->post('location');

        $this->db->update('showroom',  $data);

    }





    public function add(){

        $data['name'] = $this->input->post('name');

        $data['location'] = $this->input->post('location');

        $this->db->insert('showroom' , $data);

    }



    public function delete($id){



        $this->db->where(array('showroom_id' => $id));

        $this->db->limit(1);

        $query = $this->db->get('expense');



        if($query->num_rows() > 0){

            return FALSE;

        }



        $this->db->where(array('showroom_id' => $id));

        $this->db->limit(1);

        $query = $this->db->get('transfer');



        if($query->num_rows() > 0){

            return FALSE;

        }





        $this->db->where(array('showroom_id' => $id));

        $this->db->limit(1);

        $query = $this->db->get('salary');



        if($query->num_rows() > 0){

            return FALSE;

        }



        $this->db->where(array('showroom_id' => $id));

        $this->db->limit(1);

        $query = $this->db->get('sell');



        if($query->num_rows() > 0){

            return FALSE;

        }





        $this->db->delete('showroom', array('id' => $id));

        return TRUE;

    }



    public function sells(){

        $from_date = $this->input->get("from_date" , true);

        $to_date = $this->input->get('to_date' , true);

        $showroom_id = $this->input->get("showroom_id" , true);


        $sql = "SELECT 
                    sum(discount) as total_discount, 
                    count(*) as item_count, 
                    date_format(added_on , '%Y-%m-%d') as date,
                    sum(item.sell_price) as total_amount 
                    FROM memo
                    join memo_item on memo.id = memo_item.memo_id
                    join item on item.id = memo_item.item_id
                    where memo.showroom_id = $showroom_id
                    AND date_format(added_on , '%m/%d/%Y') >= '$from_date'
                    AND date_format(added_on , '%m/%d/%Y') <= '$to_date'

                    group by date_format(added_on , '%Y-%m-%d')";

        $query = $this->db->query($sql);

        return $query->result();

    }

    public function returns() {

        $from_date = $this->input->get("from_date" , true);
        $to_date = $this->input->get('to_date' , true);
        $showroom_id = $this->input->get("showroom_id" , true);

        $sql = "SELECT 
                    discount, 
                    count(*) as item_count, 
                    date_format(added_on , '%Y-%m-%d') as date, 
                    sum(item.sell_price) as total_return_amount 
                    FROM memo 
                join returned_item on memo.id = returned_item.memo_id 
                join item on item.id = returned_item.item_id 
                where memo.showroom_id = $showroom_id 
                    AND date_format(added_on , '%m/%d/%Y') >= '$from_date' 
                    AND date_format(added_on , '%m/%d/%Y') <= '$to_date' 
                group by date_format(added_on , '%Y-%m-%d')";

        $query = $this->db->query($sql);
        return $query->result();

    }





    /**

    * returns all the sells of a single day on a single showroom.

    */

    public function memos_single_showroom_sells() {

        $date = $this->input->get('select_date');

        $showroom_id = $this->input->get("showroom_id");



        $sql = "SELECT memo.id, memo.token as memo_token,memo.salesman, date_format(added_on , '%h:%i %p') as time,

                count(*) as item_count, sum(item.sell_price) as cash_billed, discount FROM `memo` 

                join memo_item on memo_item.memo_id = memo.id join item on 

                memo_item.item_id = item.id WHERE memo.showroom_id = $showroom_id AND 

                date_format(added_on , '%m/%d/%Y') = '$date' group by memo.id";

        $query = $this->db->query($sql);

        return $query->result();

    }



    /**

    * Returns all the returns of a memo of a showroom on a singleday.

    */

    public function memos_single_showroom_returns() {

        $date = $this->input->get('select_date');

        $showroom_id = $this->input->get("showroom_id");



        $sql = "SELECT memo.id, memo.token as memo_token, count(*) as return_count,  sum(item.sell_price) as return_amount, 

                date_format(added_on, '%h:%i %p') as time, memo.salesman from memo

                join returned_item on returned_item.memo_id = memo.id

                join item on returned_item.item_id = item.id

                where memo.showroom_id = $showroom_id AND date_format(added_on , '%m/%d/%Y') = '$date'

                group by memo.id";

        $query = $this->db->query($sql);

        return $query->result();

    }



    public function memo_detail_sold($memo_id) {

        $sql = "select item.id as item_id, item_type.name as item_type, size.name as size,

                item.color_code as color,

                item.designer_style,

                item.sell_price from memo join memo_item on memo.id = memo_item.memo_id

                join item on item.id = memo_item.item_id

                join size on item.size_id = size.id

                join item_type on size.item_type_id = item_type.id

                where memo.id = $memo_id";

        $query = $this->db->query($sql);

        return $query->result();

    }





    public function memo_detail_return($memo_id) {

        $sql = "select item.id as item_id, item_type.name as item_type, size.name as size,

                item.color_code as color,

                item.designer_style,

                item.sell_price from memo 

                join returned_item on memo.id = returned_item.memo_id

                join item on item.id = returned_item.item_id

                join size on item.size_id = size.id

                join item_type on size.item_type_id = item_type.id

                where memo.id = $memo_id";



        $query = $this->db->query($sql);

        return $query->result();

    }





    public function get_discount($memo_id) {

        $sql = "SELECT `discount` FROM `memo` WHERE id = $memo_id";

        $query = $this->db->query($sql);

        $result = $query->result();

        $first_row = $result[0];

        return $first_row->discount;

        

    }



    public function get_showroom($memo_id) {

        $sql = "select showroom.name as showroom from memo join showroom on showroom.id = memo.showroom_id

                where memo.id = $memo_id";

        $query = $this->db->query($sql);

        

        $result = $query->result();

        $first_row = $result[0];



        return $first_row->showroom;

    }





    /**

    * Gets date and showroom id from GET Request.

    * Returns the sold items.

    */

    public function get_sold_items() {

        $date = $this->input->get("select_date");

        $showroom_id = $this->input->get("showroom_id");



        $sql = "select memo.id as memo_id, memo.token as memo_token, item.id as item_id, item_type.name as item_type, size.name as size,

                item.color_code as color,

                item.designer_style,

                item.sell_price 



                from memo 



                join memo_item on memo.id = memo_item.memo_id

                join item on item.id = memo_item.item_id

                join size on item.size_id = size.id

                join item_type on size.item_type_id = item_type.id



                WHERE memo.showroom_id = $showroom_id AND date_format(memo.added_on , '%m/%d/%Y') = '$date'";

        $query = $this->db->query($sql);

        return $query->result();

    }







    /**

    * Gets date and showroom id from GET Request.

    * Returns the returned items.

    */

    public function get_returned_items() {

        $date = $this->input->get("select_date");

        $showroom_id = $this->input->get("showroom_id");



        $sql = "select memo.id as memo_id, item.id as item_id, item_type.name as item_type, size.name as size,

                item.color_code as color,

                item.designer_style,

                item.sell_price 



                from memo 



                join returned_item on memo.id = returned_item.memo_id

                join item on item.id = returned_item.item_id

                join size on item.size_id = size.id

                join item_type on size.item_type_id = item_type.id



                WHERE memo.showroom_id = $showroom_id AND date_format(memo.added_on , '%m/%d/%Y') = '$date'";

        $query = $this->db->query($sql);

        return $query->result();

    }



    /**

    * Takes date and showroom id as input froom GET. Then returns the customer payments on that day.

    */

    public function get_total_discount(){

        $showroom_id = $this->input->get("showroom_id" , true);

        $date = $this->input->get("select_date" , true);

        $sql = "SELECT sum(discount) as total_discount FROM `memo` WHERE memo.showroom_id = $showroom_id AND date_format(memo.added_on , '%m/%d/%Y') = '$date'";

        



        $query = $this->db->query($sql);

        $result = $query->result();

        $first_row = $result[0];

        return $first_row->total_discount;

    }

}