
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





    public function sales_report_all() {

        $from_date = $this->input->get("from_date" , true);
        $to_date = $this->input->get('to_date' , true);
        $showroom_id = $this->input->get("showroom_id" , true);

        $type = $this->input->get("type", true);


        $sql = "select 
                sum(m.discount) as total_discount, 
                    showroom.name, 

            if(tbl_price.total_sell_price IS NULL, 0, tbl_price.total_sell_price) as total_sale,
                    date_format(m.added_on, '%m/%d/%Y') as date,

                    if(tbl_change_price.total_change_sell_price IS NULL, 0, tbl_change_price.total_change_sell_price) as total_change,
                    if(tbl_price.total_sold_count IS NULL, 0, tbl_price.total_sold_count) as total_sale_count,
                    if(tbl_change_price.total_change_count IS NULL, 0, tbl_change_price.total_change_count) as total_change_count,
                    if(tbl_price.total_sold_count IS NULL, 0, tbl_price.total_sold_count)
                    -
                    if(tbl_change_price.total_change_count IS NULL, 0, tbl_change_price.total_change_count)
                    as total_count,

                    if(tbl_price.total_sell_price IS NULL, 0, tbl_price.total_sell_price)
                    -  if(tbl_change_price.total_change_sell_price IS NULL, 0, tbl_change_price.total_change_sell_price)
                    -
                    sum(m.discount)
                    as total

                    from memo m 

                join showroom on showroom.id = m.showroom_id

                LEFT  JOIN
                    (
                    SELECT
                    count(*) as total_sold_count,
                    date_format(mp.added_on, '%Y-%m-%d') as date,
                    showroom.name as showroom,
                    sum(item.sell_price) as total_sell_price
                FROM `memo` mp

                join showroom on showroom.id = mp.showroom_id
                join memo_item on mp.id = memo_item.memo_id
                join item on item.id = memo_item.item_id
                WHERE 
                date_format(mp.added_on, '%m/%d/%Y') between '$from_date' and '$to_date'
                GROUP BY 
                date_format(mp.added_on, '%Y-%m-%d'), showroom.id

                    ) tbl_price on tbl_price.showroom = showroom.name and tbl_price.date = date_format(m.added_on, '%Y-%m-%d')



                LEFT JOIN
                    (
                    SELECT
                    count(*) as total_change_count,
                    date_format(mrp.added_on, '%Y-%m-%d') as date,
                    showroom.name as showroom,
                    sum(item.sell_price) as total_change_sell_price
                FROM `memo` mrp

                join showroom on showroom.id = mrp.showroom_id
                join returned_item on mrp.id = returned_item.memo_id
                join item on item.id = returned_item.item_id
                WHERE 
                date_format(mrp.added_on, '%m/%d/%Y') between '$from_date' and '$to_date'
                GROUP BY 
                date_format(mrp.added_on, '%Y-%m-%d'), showroom.id

                    ) tbl_change_price on tbl_change_price.showroom = showroom.name and tbl_change_price.date = date_format(m.added_on, '%Y-%m-%d')

where date_format(m.added_on , '%m/%d/%Y') between '$from_date' and '$to_date'
group by date_format(m.added_on, '%m/%d/%Y'), m.showroom_id

UNION ALL


select 
      sum(m.discount) as total_discount, 

                    showroom.name,

            if(tbl_price.total_sell_price IS NULL, 0, tbl_price.total_sell_price) as total_sale,
                    date_format(m.added_on, '%m/%d/%Y') as date,
                    if(tbl_change_price.total_change_sell_price IS NULL, 0, tbl_change_price.total_change_sell_price) as total_change,
                    if(tbl_price.total_sold_count IS NULL, 0, tbl_price.total_sold_count) as total_sale_count,
                    if(tbl_change_price.total_change_count IS NULL, 0, tbl_change_price.total_change_count) as total_change_count,
                    if(tbl_price.total_sold_count IS NULL, 0, tbl_price.total_sold_count)
                    -
                    if(tbl_change_price.total_change_count IS NULL, 0, tbl_change_price.total_change_count)
                    as total_count,
                    if(tbl_price.total_sell_price IS NULL, 0, tbl_price.total_sell_price)
                    -  if(tbl_change_price.total_change_sell_price IS NULL, 0, tbl_change_price.total_change_sell_price)
                    -
                    sum(m.discount)
                    as total

                    from memo m 
                join showroom on showroom.id = m.showroom_id
                RIGHT JOIN
                    (
                    SELECT
                    count(*) as total_sold_count,
                    date_format(mp.added_on, '%Y-%m-%d') as date,
                    showroom.name as showroom,
                    sum(item.sell_price) as total_sell_price
                FROM `memo` mp

                join showroom on showroom.id = mp.showroom_id
                join memo_item on mp.id = memo_item.memo_id
                join item on item.id = memo_item.item_id
                WHERE 
                date_format(mp.added_on, '%m/%d/%Y') between '$from_date' and '$to_date'
                GROUP BY 
                date_format(mp.added_on, '%Y-%m-%d'), showroom.id

                    ) tbl_price on tbl_price.showroom = showroom.name and tbl_price.date = date_format(m.added_on, '%Y-%m-%d')
                RIGHT JOIN
                    (
                    SELECT
                    count(*) as total_change_count,
                    date_format(mrp.added_on, '%Y-%m-%d') as date,
                    showroom.name as showroom,
                    sum(item.sell_price) as total_change_sell_price
                FROM `memo` mrp

                join showroom on showroom.id = mrp.showroom_id
                join returned_item on mrp.id = returned_item.memo_id
                join item on item.id = returned_item.item_id
                WHERE 
                date_format(mrp.added_on, '%m/%d/%Y') between '$from_date' and '$to_date'
                GROUP BY 
                date_format(mrp.added_on, '%Y-%m-%d'), showroom.id

                    ) tbl_change_price on tbl_change_price.showroom = showroom.name and tbl_change_price.date = date_format(m.added_on, '%Y-%m-%d')

where date_format(m.added_on , '%m/%d/%Y') between '$from_date' and '$to_date'
group by date_format(m.added_on, '%m/%d/%Y'), m.showroom_id";

        $query = $this->db->query($sql);
        $results = $query->result();
        $reports = array();
        foreach ($results as $result) {
            $reports[$result->date][$result->name] = $type == "price" ? $result->total : $result->total_count;
        }

        return $reports;
    }




    public function sells(){

        $from_date = $this->input->get("from_date" , true);
        $to_date = $this->input->get('to_date' , true);
        $showroom_id = $this->input->get("showroom_id" , true);
             
        $sql = "select 
                    sum(m.discount) as total_discount, 
                    date_format(m.added_on, '%d-%m-%Y') as date,
                    sbm.itemcount as sold_items,
                    sbm.total_sale,
                    return_table.return_item_count,
                    return_table.return_total_price
                from memo m 
                left outer join (
                    select 
                        m4.*, 
                        count(*) as itemcount,
                        sum(item.sell_price) as total_sale
                    from memo m4 
                    join memo_item on m4.id = memo_item.memo_id
                    join item on item.id = memo_item.item_id
                    where m4.showroom_id = $showroom_id
                    group by date_format(m4.added_on, '%d-%m-%Y')
                    ) 
                    sbm on date_format(sbm.added_on, '%d-%m-%Y') = date_format(m.added_on, '%d-%m-%Y')
                left outer join (
                    select 
                        m5.*, 
                        count(*) as return_item_count,
                        sum(item.sell_price) as return_total_price
                    from memo m5 
                    join returned_item on m5.id = returned_item.memo_id
                    join item on item.id = returned_item.item_id
                    where m5.showroom_id = $showroom_id
                    group by date_format(m5.added_on, '%d-%m-%Y')
                    ) 
                    return_table on date_format(return_table.added_on, '%d-%m-%Y') = date_format(m.added_on, '%d-%m-%Y')
                where date_format(m.added_on , '%m/%d/%Y') between '$from_date' and '$to_date'
                and m.showroom_id = $showroom_id
                group by date_format(m.added_on, '%d-%m-%Y') order by m.added_on asc";
        $query = $this->db->query($sql);
        //die(var_dump($query->result()));
        $s = $query->result();
        return $s;
    }




    /**
    * currently not being used
    */
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