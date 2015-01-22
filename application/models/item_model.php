<?php

/**

 * Created by JetBrains PhpStorm.

 * User: Anik

 * Date: 10/13/13

 * Time: 11:10 AM

 * To change this template use File | Settings | File Templates.

 */



class Item_model extends CI_Model{

    function __construct(){

        parent::__construct();

    }





    public function get_items($select = '*' , $where = array() , $join = array() , $limit = '') {



        $this->db->where($where);

        $this->db->select($select);

        $this->db->from('items');

        if(in_array('outlet', $join)){

            $this->db->join('outlet', 'outlet.id = items.outlet_id');

        }

        if(in_array('purchases' , $join)){

            $this->db->join('purchases', 'purchases.id = items.purchases_id');

        }

        $query = $this->db->get();



        if($limit != '') {

            $this->db->limit($limit);

        }





        return $query->result();

    }





    /**

    * retuns items that has the following barcode where item's current locatoin is 

    */





    public function get_item_json($barcode , $showroom_id = 1){



        $item_id = intval($barcode);



        $query = "SELECT item.id as id, item_type.name as item_type, size.name as size, showroom.name as current_location ,  item.purchase_id , item.sell_price, item.designer_style, supplier.name as supplier, CONCAT( DATE_FORMAT( item.created_at , '%Y%m' ) , item.id ) as barcode FROM `item` join size on size.id = item.size_id join item_type on item_type.id = size.item_type_id join supplier on item.supplier_id = supplier.id join showroom on showroom.id = item.showroom_id where item.id = ".$item_id." AND item.showroom_id = ".$showroom_id;



        $query = $this->db->query($query);



        if($query->num_rows()>0){

            $res = $query->result();

            return $res[0];

        }else{

            return null;

        }

    }





    public function get_items_by_purchase_id($purchase_id){



        $query = "SELECT item.id as id, item_type.name as item_type, size.name as size, showroom.name as current_location ,  item.purchase_id , item.sell_price, item.designer_style, supplier.name as supplier, CONCAT( DATE_FORMAT( item.created_at , '%Y%m' ) , item.id ) as barcode FROM `item` join size on size.id = item.size_id join item_type on item_type.id = size.item_type_id join supplier on item.supplier_id = supplier.id join showroom on showroom.id = item.showroom_id where item.showroom_id = 1 AND purchase_id = ".$purchase_id;

        $queryObj = $this->db->query($query);



        if($queryObj->num_rows() > 0){

            return $queryObj->result();

        }else{

            return null;

        }

    }



    public function get_barcodes($purchase_id){

        $sql = "select item.*, size.name as size_name , item_type.name as item_name from item 

                    join size on size.id =  item.size_id

                    join item_type on item_type.id = size.item_type_id

                    where purchase_id = ".$purchase_id;

        $query = $this->db->query($sql);

        return $query->result();

    }



    public function search() {

        $size = $this->input->post("size" , true);
        $color = $this->input->post("color" , true);
        $style = $this->input->post("style" , true);

        $sql =  "select 
                    count(*) as item_count,
                    showroom.name as showroom_name, 
                    showroom.location as showroom_location from item 
                    join showroom on showroom.id = item.showroom_id 
                    where 
                        item.designer_style = '".$style."' 
                        and item.color_code = '".$color."' 
                        and item.size_id = $size  
                        and item.id not in (select * from sold_item)
                    group by `showroom_id`";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    public function get_stock($where, $groupby) {
        $where = $where == '' ? 1 : $where;
        $sql = "SELECT 
                    count(*) as quantity,
                    sum(item.sell_price) as total_price,
                    item_type.name as type,
                    size.name as size,
                    item.sell_price as price,
                    item.color_code,
                    item.designer_style,
                    showroom.name as showroom,
                    `group`.id, sub_group.id, item_type.id, size.id
                    FROM `item` 
                join size on size.id = item.size_id
                join item_type on item_type.id = size.item_type_id
                join sub_group on sub_group.id = item_type.sub_group_id
                join `group` on group.id = sub_group.group_id
                join showroom on showroom.id = item.showroom_id";
        $sql .= ' where '.$where." group by ".$groupby;

        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;        
    }



    public function get_purchase_info() {
        $get = $this->input->get();
        $where = '';

        if($get['color_code'] != '') {
            $where = " where item.color_code = '".$get['color_code']."'";
        }

        if($get['designer_style'] != '') {
            if($where != '') {
                $where = ' and ';
            }
            $where = " where item.designer_style = '".$get['designer_style']."'";
        }
        
        if($get['from_date']!= '' && $get['to_date']!='') {
            if($where != '') {
                $where .= " and ";
            } else {
                $where = " where ";
            }
            $where .= "DATE_FORMAT(purchase.created_at,'%m/%d/%Y') between '".$get['from_date']."' and '".$get['to_date']."'";
        }


        $groupby = "";
        switch ($get['display']) {
            case "item-type":
                $groupby = ' group by item_type.id';
                break;
            case "color":
                $groupby = ' group by item.color_code';
                break;                
            case "style":
                $groupby = ' group by item.designer_style';
                break;            
            case "date":
                $groupby = ' group by purchase_date';
                break;
            case "size":
                $groupby = ' group by size.id';
                break;
        }
        

        //$groupby .= " , purchase.id";

        $sql = "SELECT 
                    count(*) as quantity,
                    sum(item.sell_price) as price,
                    DATE_FORMAT(purchase.created_at,'%m/%d/%Y') as purchase_date,
                        purchase.id as purchase_id,
                        item.color_code,
                        item.designer_style,
                        size.name as size,
                        item_type.name as type
                    FROM `purchase` 
                        join item on item.purchase_id = purchase.id
                        join size on size.id = item.size_id
                        join item_type on item_type.id = size.item_type_id
                    ".$where." ".$groupby." order by purchase.id desc";

        // $sql = "SELECT 
        //             purchase.id as purchase_id,
        //             DATE_FORMAT(purchase.created_at,'%Y-%m-%d') AS purchase_date, 
        //             count( * ) AS quantity 
        //             FROM purchase 
        //                 LEFT JOIN item ON purchase.id = item.purchase_id 

        //                 GROUP BY purchase.id 
        //                 order by purchase.id desc";
        //echo $sql;  
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }



}

