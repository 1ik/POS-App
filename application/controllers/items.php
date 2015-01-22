<?php

/**

 * Created by JetBrains PhpStorm.

 * User: Anik

 * Date: 10/13/13

 * Time: 12:03 PM

 * To change this template use File | Settings | File Templates.

 */



class Items extends CI_Controller{

    function __construct(){
        parent::__construct();

        $this->load->model('item_model');
    }



    public function index() {
        // $data['vars']['items'] = $this->item_model->get_items();
        // $data['main_content'] = 'items/show';
        // $data['active'] = 'item';
        // $this->load->view('template' , $data);
    }



    public function purchase($id){
        is_staff();

        $data['vars']['purchase_id'] = $id;
        $data['vars']['items'] = $this->item_model->get_items('*' , array('purchases_id' => $id));
        $data['main_content'] = 'items/show';
        $data['active'] = 'item';
        $this->load->view('template' , $data);

    }



    public function show(){
        is_staff();

        if(!$this->input->post()){
            return;
        }

        $data['active'] = 'item';

        $designer_style = $this->input->post('designer_style');

        $data['vars']['items'] = $this->item_model->get_items('items.*' , array('designer_style' => $designer_style),array('purchases') );

        $this->session->set_flashdata('message' , "showing items with designer style : $designer_style");

        $data['main_content'] = 'items/show';

        $this->load->view('template' , $data);

    }



    /**
     * Takes barcode and retursn the item. it serves json requests.
     * @param $barcode the combination of YEAR_MONTH_ID .
     *
     */

    public function get_items_json($barcode , $showroom_id = 1){

        $item_object = $this->item_model->get_item_json($barcode , $showroom_id);

        echo json_encode($item_object);

    }



    /**
    * Gets the items in json format assosiated with the purchase id. (Only available in Headoffice.)
    */

    public function get_items_by_purchase_id($purchase_id){

        echo json_encode($this->item_model->get_items_by_purchase_id($purchase_id));

    }



    /**
    * Receives item's size name, color code in post request.
    * Returns showrooms having the items. Returns items number and showroom name and showroom locatoin in json array.
    */

    public function search() {
        if(!$this->_can_see_sell_info()) {
            redirect('auth');
        }

        if($this->input->post()) {

            echo json_encode($this->item_model->search());

        } else {

            $this->load->model("group_model");
            $data['groups'] = $this->group_model->get_groups();
            $data['page_name'] = 'Item search';
            $data['active'] = 'item';
            $data['vars'] = '';
            $data['main_content'] = 'items/search';
            $this->load->view('template' , $data);
        }
        //echo json_encode($this->item_model->search());
    }


    public function stock() {
        if($this->input->get()) {
            $get = $this->input->get();
            $groupby = '';
            $where = '';

            switch ($get['display']) {
                case "item-type":
                    $groupby = 'item_type.id';
                    break;
                case "size":
                    $groupby = 'size.id';
                    break;
                case "color":
                    $groupby = 'color_code';
                    break;
                case "style":
                    $groupby = 'designer_style';
                    break;                    
            }

            switch ($get["status"]) {
                case 'sold':
                    $where = "item.id in (select * from sold_item)";
                    break;
                case 'not_sold':
                    $where = "item.id not in (select * from sold_item)";
                    break;
                case "both" :
                    $where = '';
            }

            if($get['showroom_id'] != -1) {
                if( $where != '' ) {
                    $where .= " and ";
                }
                $where .= " showroom.id = ".$get['showroom_id'];
            }

            if($get['designer_style'] != '') {
                if($where != '') {
                    $where .= " and ";
                }

                $where .= " item.designer_style = '".$get['designer_style']."'";
            }

            $groupby .= ' ,price';
            

            $this->load->model('item_model');
            $data['vars']['rows'] = $this->item_model->get_stock($where, $groupby);

        } else {
            $data['vars'] = '';            
        }

        $this->load->model('showroom_model');
        $data['showrooms'] = $this->showroom_model->get('*');    
        $data['page_name'] = 'Item stock information';
        $data['active'] = 'item_stock';
        $data['main_content'] = 'items/stock';
        $data['links'] = array(anchor('items/stock' , 'Stock information') , anchor('items/purchase_data' , "purchase information"));
        $this->load->view('template' , $data);
    }



    public function purchase_data() {
        if($this->input->get()) {
            $this->load->model('item_model');
            $data['vars']['rows'] = $this->item_model->get_purchase_info();
        } else {            
            $data['vars'] = '';
        }

        $data['page_name'] = "purchase infromatoin";
        $data['main_content'] = "items/purchase_info";
        $data['links'] = array(anchor('items/stock' , 'Stock information') , anchor('items/purchase_data' , "purchase information"));
        $data['active'] = 'item_stock';
        $this->load->view('template' , $data);
    }


    private function _can_see_sell_info() {
        $CI =& get_instance();

        if($CI->ion_auth->in_group('members' , $CI->ion_auth->get_user_id()) || $CI->ion_auth->in_group('staff' , $CI->ion_auth->get_user_id()) || !$CI->ion_auth->in_group('admin' , $CI->ion_auth->get_user_id())) {
            return true;
        }
        return false;
    }






    function load_view(&$data){
        $this->load->view('template' , $data);
    }





    public function audit($designer_style = '') {
        if($designer_style == '') {
            $data['page_name'] = "Audit";
            $data['vars'] = '';
            $data['active'] = 'audit';
            $data['main_content'] = 'items/audit_form';
            $this->load_view($data);
            return;
        }


        //get the sizes associated with these desginer style number.
        $sql = "SELECT distinct size.name FROM `item`
                    join size on size.id = item.size_id
                    WHERE `designer_style` = '$designer_style' order by size.name";
        $sizes = $this->db->query($sql);



        $size_array = $sizes->result();
        

        //prepare purchase report.
        $sql = "SELECT
                        purchase_id, 
                        purchase.created_at, 
                        color_code, 
                        size.name as size,
                        count(*) as total_purchase_count FROM item 
                    join size on size.id = item.size_id
                    join purchase on item.purchase_id = purchase.id
                    WHERE designer_style = '$designer_style'
                group by size_id, purchase_id, color_code";
        $query = $this->db->query($sql);
        $purchase_array = $query->result();



        $purchase_report = array();

        foreach ($purchase_array as $purchase) {
            $purchase_report[$purchase->purchase_id]['created_at'] = $purchase->created_at;
            $purchase_report[$purchase->purchase_id]['report'][$purchase->color_code][$purchase->size] = $purchase->total_purchase_count;
        }


        //prepare the transfer report.
        $sql = "select 
                    count(*) as transfer_count, 
                    s.name as size,
                    i.color_code, 
                    showroom.name as showroom  
                    from transfer t
                
                join ho_showroom_transfers hot on hot.transfer_id = t.id
                join transferred_item ti on ti.transfer_id = t.id
                join item i on i.id = ti.item_id
                join size s on s.id = i.size_id
                join showroom on showroom.id = t.to_showroom_id
                
                where i.designer_style = '$designer_style'

                group by showroom.id, size, i.color_code
UNION ALL                

select 
                    count(*) as transfer_count, 
                    s.name as size, 
                    i.color_code, 
                    showroom.name as showroom  
                    from transfer t
                join showroom_to_showroom_transfers sot on sot.transfer_id = t.id
                join transferred_item ti on ti.transfer_id = t.id
                join item i on i.id = ti.item_id
                join size s on s.id = i.size_id
                join showroom on showroom.id = t.to_showroom_id
                where i.designer_style = '$designer_style'
                group by showroom.id, size, i.color_code
order by showroom, size, color_code";

        $query = $this->db->query($sql);

        $transfer_array = $query->result();

        $transfer_report = array();

        foreach ($transfer_array as $transfer) {
            $transfer_report[$transfer->showroom][$transfer->color_code][$transfer->size] = $transfer->transfer_count;
        }


        //NOW PAREPARE THE SALES.
        $sql = "select 
                    showroom.name as showroom,
                    color_code,
                    size.name as size, 
                    count(*) as total_sale
                    from item

                    join sold_item on item.id = sold_item.item_id
                    join size on size.id = item.size_id
                    join showroom on showroom.id = item.showroom_id

                where designer_style = '$designer_style'
                group by showroom_id, size, color_code
                order by showroom_id, size.name, color_code";
        
        $query = $this->db->query($sql);
        $sales_array = $query->result();

        $sales_report = array();

        foreach ($sales_array as $sale) {
            $sales_report[$sale->showroom][$sale->color_code][$sale->size] = $sale->total_sale;
        }

        //NOW PREPARE THE STOCK REPORT.
        $sql = "SELECT 
                    count(*) as total_count,
                    size.name as size,
                    item.color_code,
                    showroom.name as showroom
                FROM `item`
                    join size on size.id = item.size_id
                    join showroom on item.showroom_id = showroom.id
                    where designer_style = '$designer_style' and item.id not in (select item_id from sold_item)
                    group by size.name, item.color_code, showroom.name";

        $query = $this->db->query($sql);
        $stock_array = $query->result();

        $stock_report = array();
        foreach ($stock_array as $stock) {
            $stock_report[$stock->showroom][$stock->color_code][$stock->size] = $stock->total_count;
        }

        
        $data['sizes'] = $size_array;
        $data['purchase_report'] = $purchase_report;
        $data['transfer_report'] = $transfer_report;
        $data['sales_report'] = $sales_report;
        $data['stock_report'] = $stock_report;

        $this->load->view('items/audit', $data);
    }



    public function discount() 
    {

        if($this->input->post()) 
        {
            $discounts = $this->input->post();
            unset($discounts['allow']);
            $this->db->trans_start();
            foreach ($discounts as $designer_style => $percent) {
                $percent = $percent == "" ? 0 : $percent;
                $sql = "INSERT INTO discounts( designer_style, percent ) VALUES('$designer_style', $percent) ON DUPLICATE KEY UPDATE percent = VALUES(percent)";
                $this->db->query($sql);
            }
            $this->db->trans_complete();
        }

        $sql = "SELECT distinct(item.designer_style) as designer_style, percent FROM `item` left outer join discounts on discounts.designer_style = item.designer_style order by designer_style asc";
        $query = $this->db->query($sql);
        //show discount form.
        $data['page_name'] = "Discount";
        $data['vars']['discounts'] = $query->result();
        $data['main_content'] = 'items/discounts';
        $data['active'] = 'discount';
        $this->load_view($data);

    }

}