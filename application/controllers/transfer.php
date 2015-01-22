<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Anik
 * Date: 10/18/13
 * Time: 9:25 AM
 * To change this template use File | Settings | File Templates.
 */



class Transfer extends CI_Controller {

    function __construct(){

        parent::__construct();

        is_staff();

        $this->load->model('transfer_model');

    }



    function load_view(&$data){

        $data['links'][] = anchor('transfer' , 'HeadOffice To Showrooms');
        $data['links'][] = anchor('transfer/from/showroom' , 'Showrooms To Showrooms');
        $data['links'][] = anchor('transfer/from/customer' , 'Changes from Customers');

        $data['links'][] = anchor('transfer/create' , 'Transfer Items');
        //$data['links'][] = anchor('transfer/return_to_headoffice' , 'Return To HeadOffice');

        $data['active'] = 'transfer';

        $this->load->view('template' , $data);

    }



    public function dump_ho_shwroom_ids () {
        $string = "INSERT INTO `pixeliz1_inventory`.`ho_showroom_transfers` (`id`, `transfer_id`) VALUES";

        $this->load->model("transfer_model");
        $transfers = $this->transfer_model->get_customer_changes_transfers();
        $d = '';

        foreach ($transfers as $transfer) {
            $d = $d."(NULL, '".$transfer->transfer_id."')";
            $d = $d.',';
        }

        $string = $string.$d;
        echo $string;

    }



    public function index(){

        $data['main_content'] = 'transfer/show';

        $data['page_name'] = 'HeadOffice to Showroom Transfers';

        //show all the transfers.
        $data['vars']['transfers'] = $this->transfer_model->get();
        
        $this->load_view($data);
    }




    public function from($from = '') {
        $data = '';
        if($from == 'showroom') {

            $transfer_data = $this->transfer_model->get_showroom_to_showroom_transfers();
            $data['page_name'] = 'Showroom to shoroom transfers';
            $data['vars']['type'] = 'showroom_to_showroom';

        } else if($from == 'customer') {

            $transfer_data = $this->transfer_model->get_customer_changes_transfers();
            $data['page_name'] = 'Customers change Transfers';
            $data['vars']['type'] = 'customer_change';
        }

        $data['vars']['transfers'] = $transfer_data;
        $data['main_content'] = 'transfer/show';

        $this->load_view($data);
    }





    public function create(){

        $this->load->model('showroom_model');
        $data['vars']['showrooms'] = $this->showroom_model->get();
        $data['main_content'] = 'transfer/create';
        $data['page_name'] = 'create new transfer';
        $this->load_view($data);
    }



    //receives json data via get request. from transfer/create.php file.
    public function create_submit_json(){

        $json_data = $this->input->post('val');
        $this->transfer_model->add($json_data);
        echo 'ok';
    }


    public function return_to_headoffice(){

        $this->load->model('showroom_model');
        $data['vars']['showrooms'] = $this->showroom_model->get();
        $data['main_content'] = 'transfer/create_return';
        $data['page_name'] = 'create new transfer';
        $this->load_view($data);
    }


    public function detail($transfer_id = '') {

        if($transfer_id == ''){
            die();
        }

        $data['vars']['items'] = $this->transfer_model->get_items_by_transfer_id($transfer_id);
        $data['main_content'] = 'transfer/detail';
        $data['page_name'] = 'Transfer detail';
        $this->load_view($data);        
    }


    //shows a rport of that transferid.
    public function report($transfer_id) {
        $data['items'] = $this->transfer_model->get_report_data($transfer_id);
        $data['transfer_id'] = $this->transfer_model->get_contextual_id($transfer_id);
        $this->load->view("transfer/report",$data);
    }



    /**
     * Receives the transfer id from url
     * then makes its reached coloumn true in the transfers table.
     * @return void
     * @author 
     **/
    public function make_received( $transfer_id ) {
        $update_data = array('reached' => true);
        $this->db->where('id', $transfer_id );
        $this->db->update('transfer', $update_data);
        redirect('transfer/from/showroom');
    }

    /**
     * Does the opposite work of make_received.
     * @return void
     * @author 
     **/
    public function make_unreceived( $transfer_id ) {
        $update_data = array('reached' => false);
        $this->db->where('id', $transfer_id );
        $this->db->update('transfer', $update_data);
        redirect('transfer/from/showroom');
    }

}