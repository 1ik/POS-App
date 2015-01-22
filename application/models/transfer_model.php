<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Anik
 * Date: 10/18/13
 * Time: 9:28 AM
 * To change this template use File | Settings | File Templates.
 */



class Transfer_model extends CI_Model {

    public function __construct(){

        parent::__construct();

    }



    public function get(){

        $sql = "SELECT  count(*) as number_of_items,
                    f.name as from_showroom, 
                    t.name as to_showroom, 
                    transfer.id as transfer_id, 
                    transfer.created_at as transfer_time,
                    hst.id as ho_shwrm_transfer_id
                    FROM `transfer`
                    join transferred_item on transfer.id = transferred_item.transfer_id
                    join showroom as f on f.id = transfer.from_showroom_id
                    join showroom as t on t.id = transfer.to_showroom_id
                    left outer join ho_showroom_transfers hst on hst.transfer_id = transfer.id
                    where f.id != t.id and f.id = 1
                    group by transfer.id order by transfer.id desc";

        $query = $this->db->query($sql);

        return $query->result();

    }



    public function get_showroom_to_showroom_transfers() {

        $sql = "SELECT  count(*) as number_of_items,
                    f.name as from_showroom, 
                    t.name as to_showroom,
                    transfer.id as transfer_id, 
                    transfer.created_at as transfer_time,
                    transfer.reached,
                    hst.id as ho_shwrm_transfer_id
                    FROM `transfer`
                    join transferred_item on transfer.id = transferred_item.transfer_id
                    join showroom as f on f.id = transfer.from_showroom_id
                    join showroom as t on t.id = transfer.to_showroom_id
                    left outer join showroom_to_showroom_transfers hst on hst.transfer_id = transfer.id
                    where f.id != t.id and f.id != 1
                    group by transfer.id order by transfer.id desc";

        $query = $this->db->query($sql);

        return $query->result();
    }


    public function get_customer_changes_transfers() {

        $sql = "SELECT  count(*) as number_of_items,
                    f.name as from_showroom, 
                    t.name as to_showroom, 
                    transfer.id as transfer_id, 
                    transfer.created_at as transfer_time,
                    hst.id as ho_shwrm_transfer_id
                    FROM `transfer`
                    join transferred_item on transfer.id = transferred_item.transfer_id
                    join showroom as f on f.id = transfer.from_showroom_id
                    join showroom as t on t.id = transfer.to_showroom_id
                    left outer join customer_return_transfers hst on hst.transfer_id = transfer.id
                    where f.id = t.id and f.id != 1
                    group by transfer.id order by transfer.id desc";

        $query = $this->db->query($sql);
        return $query->result();

    }






    public function get_items_by_transfer_id($transfer_id) {

        $sql = "SELECT item.id as item_id, item_type.name as item_type, size.name as size, item.sell_price as item_price, item.designer_style, supplier.name as supplier_name , 
                purchase.id as purchase_id
                FROM `transfer` 
                join transferred_item on transferred_item.transfer_id = transfer.id
                join item on transferred_item.item_id = item.id
                join size on item.size_id = size.id
                join supplier on item.supplier_id = supplier.id
                join item_type on size.item_type_id = item_type.id
                join purchase on  purchase.id = item.purchase_id
                WHERE transfer.id = ".$transfer_id;

        $query  = $this->db->query($sql);

        return $query->result();

    }





    //it is used only for transfer items from Headoffice to showrooms.
    public function add($json_data){

        $this->db->trans_start();

        $transfer = json_decode($json_data);
        $showroom_id = $transfer->showroom_id;
        $items = $transfer->items;
        $date = new DateTime();



        //first open a transfer.
        //from_showroom_id is 1 because we know its only used for Headoffice.

        $this->db->insert('transfer' , array('from_showroom_id' => 1, 'to_showroom_id' => $showroom_id , 'created_at' => $date->format('Y-m-d H:i:s') ));

        $transfer_id = $this->db->insert_id();



        $data = array();

        $item_updates = array();



        

        $values = '';

        $i=0;

        foreach($items as $item_id){

            if($item_id != null) {

                $data[] = array('transfer_id' => $transfer_id , 'item_id' => $item_id);

                $values = $values." (".$item_id.",".$showroom_id.") ";

                if($i < count($items)-1){

                    $values = $values . ","; //append comma only after the prior rows to the LAST one.

                }

            }

            $i++;

        }

        $updates = "INSERT INTO item( id, showroom_id ) VALUES".$values." ON DUPLICATE KEY UPDATE showroom_id = VALUES(showroom_id)";

        //insert transferitems.
        $this->db->insert_batch('transferred_item' , $data);
        //then update each item's current location(showroom_id)
        $this->db->query($updates);
        //doneP

        //now add the transfer_id to ho_showroom_transfers table
        $this->db->insert('ho_showroom_transfers', array( 'transfer_id' => $transfer_id ));

        $this->db->trans_complete();

    }





    public function get_report_data($transfer_id) {

        $sql = "SELECT 
                    item_type.name as item_type, 
                    size.name as size, 
                    item.color_code as color, 
                    item.designer_style , 
                    count(item.id)as quantity,
                    item.sell_price as item_price,  
                    sum(item.sell_price) as amount , 
                    from_showroom.name as source, 
                    to_showroom.name as destination,
                    hst.id as ho_shwrm_transfer_id,
                    transfer.created_at as transfer_time

                FROM `transfer`

                join transferred_item on transferred_item.transfer_id = transfer.id
                join item on transferred_item.item_id = item.id
                join size on size.id = item.size_id
                join showroom as from_showroom on from_showroom.id = transfer.from_showroom_id
                join showroom as to_showroom on to_showroom.id = transfer.to_showroom_id
                join item_type on item_type.id = size.item_type_id
                left outer join ho_showroom_transfers hst on hst.transfer_id = transfer.id

                where transfer.id = $transfer_id

                group by item.color_code, item.size_id";



        $query = $this->db->query($sql);

        return $query->result_array();

    }

    /**
    * Returns a contextual transfer id against an actual transfer id.
    */
    public function get_contextual_id($transfer_id) {
        $sql = 
            "SELECT 
                hot.id as hot_id, 
                sot.id as sot_id, 
                cot.id as cot_id, 
                t.id as real_id 
            FROM transfer as t

            left join ho_showroom_transfers as hot on hot.transfer_id = t.id
            left join showroom_to_showroom_transfers as sot on sot.transfer_id = t.id
            left join customer_return_transfers as cot on cot.transfer_id = t.id

            where t.id = ".$transfer_id;

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            $result_array = $query->result();
            $result = $result_array[0];
            if($result->hot_id != null) {
                return $result->hot_id;
            } else if($result->sot_id != null) {
                return $result->sot_id;
            } else if($result->cot_id != null) {
                return $result->cot_id;
            }
        }
        
        return -1;

    }

}