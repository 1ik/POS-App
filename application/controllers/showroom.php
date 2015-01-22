<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Anik
 * Date: 10/12/13
 * Time: 4:03 PM
 * To change this template use File | Settings | File Templates.
 */



class Showroom extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('showroom_model');
    }



    function load_view(&$data){

        $data['active'] = 'showroom';

        $data['links'][] = anchor('showroom/sell_info' , "Sales information ");

        $data['links'][] = anchor('showroom/sell_info_single_date' , "Sales information Single Day");

        $this->load->view('template' , $data);

    }





    public function index(){
        is_staff();

        //get all the outlets

        if($this->input->post()){

            $this->load->library('form_validation');

            $this->form_validation->set_rules('name' , 'Name of Store', 'required|is_unique[showroom.name]');

            $this->form_validation->set_rules('location' , 'Showroom address', 'required');

            if($this->form_validation->run() === FALSE){
                
            }else{

                //add the user to db.

                $this->showroom_model->add();

                $this->session->set_flashdata('message' , "Showroom has successfully been added");

                redirect('showroom');

            }

        }

        $data['page_name'] = 'Showrooms';

        $data['vars']['showrooms'] = $this->showroom_model->get();

        $data['main_content'] = 'showroom/show';

        $this->load_view($data);

    }






    public function edit($id){
        is_staff();

        if($this->input->post()){

            $this->load->library('form_validation');

            $this->form_validation->set_rules('name' , 'Name of Showroom', 'required');

            $this->form_validation->set_rules('location' , 'Location of Showroom', 'required');

            if($this->form_validation->run() === FALSE){
                

            }else{

                $this->showroom_model->update($id);

                $this->session->set_flashdata('message' , 'showroom has successfully edited');

                redirect('showroom');

            }

        }



        $data['page_name'] = 'Update Showroom info';

        $showrooms = $this->showroom_model->get( '*' , array('id' => $id));

        $data['vars']['showroom'] = $showrooms[0];

        $data['main_content'] = 'showroom/edit';

        $this->load_view($data);

    }



    public function delete($id){
        is_staff();


        if($this->showroom_model->delete($id)){

            $this->session->set_flashdata('message' , 'Store information has successfully been deleted');

        }else{

            $this->session->set_flashdata('message' , "Can't remove Showroom, You have existing data with this Showroom , <h3> Showroom can only be removed , only if you don't have any data with it</h3>");

        }



        redirect('showroom');

    }




    private function _can_see_sell_info() {
        $CI =& get_instance();

        if($CI->ion_auth->in_group('members' , $CI->ion_auth->get_user_id()) || $CI->ion_auth->in_group('staff' , $CI->ion_auth->get_user_id()) || !$CI->ion_auth->in_group('admin' , $CI->ion_auth->get_user_id())) {
            return true;
        }
        return false;
    }



    public function sell_info() {
        if(!$this->_can_see_sell_info()) {
            redirect('auth');
        }
        $data['vars']['showrooms'] = $this->showroom_model->get();

        if( $this->input->get()) {
                        
            if($this->input->get("showroom_id" , true) == "all") {

                $reports = $this->showroom_model->sales_report_all();
                $data['reports'] = $reports;
                $data['showrooms'] = $this->showroom_model->get('*');
                $this->load->view('showroom/report_all_showroom', $data);
                return;
            }

            $sells = $this->showroom_model->sells();
            $sell_info['from'] = $this->input->get('from_date');
            $sell_info['to'] = $this->input->get('to_date');
            $showroom = $this->showroom_model->get('name' , array('id' => $this->input->get('showroom_id')));
            $showroom = $showroom[0];
            $sell_info['showroom_name'] = $showroom->name;

            $data['vars']['reports'] = $sells;
            $data['vars']['sell_data'] = $sell_info;

            $data['page_name'] = "Sell Information";
            $data['main_content'] = 'showroom/sell_info';
            $this->load_view($data);

        } else {

            $data['page_name'] = "Sell information";

            $data['main_content'] = 'showroom/sell_info';

            $this->load_view($data);
        }
    }






    public function sell_info_single_date() {

        if(!$this->_can_see_sell_info()) {
            redirect('auth');
        }

        $data['vars']['showrooms'] = $this->showroom_model->get();

        if( $this->input->get()) {

            $this->load->model("expense_model");
            $data['vars']['expenses'] = $this->expense_model->get_expense_single_day();
            $showroom_name = $this->showroom_model->get('name' , array('id' => $this->input->get('showroom_id')));
            $showroom_name = $showroom_name[0];

            $data['vars']['sell_data']['showroom_name'] = $showroom_name->name;
            $data['vars']['sell_data']['date'] = $this->input->get("select_date");


            if($this->input->get('display_type') == "memo") {

                $sells = $this->showroom_model->memos_single_showroom_sells();
                $returns = $this->showroom_model->memos_single_showroom_returns();


                /*
                OLD DEPRECATED WAY..
                $memo_items = array();
                foreach ($sells as $sell) {

                    $memo_item = new MemoItems;
                    $memo_item->id = $sell->id;
                    $memo_item->token = $sell->memo_token;
                    $memo_item->time = $sell->time;
                    $memo_item->salesman = $sell->salesman;
                    $memo_item->cash_billed = $sell->cash_billed;
                    $memo_item->discount = $sell->discount;
                    $memo_item->sold_items = $sell->item_count;
                    $memo_items[$memo_item->id] = $memo_item;
                }


                foreach($returns as $return) {
                    if(isset($return) && isset($memo_items[$return->id])) {
                        $memo_item = $memo_items[$return->id];
                        $memo_item->returned_items = $return->return_count;
                        $memo_item->return_amount = $return->return_amount;
                    }
                }

                

                foreach($returns as $return) {
                    if(isset($return)) {
                        if(!isset($memo_items[$return->id])) {
                            //if we don't have a memo id set beforehand it means there is not sold items
                            //in this memo but here is returned item from customers.
                            $memo_item = new MemoItems;
                            $memo_item->id = $return->id;
                            $memo_item->token = $return->memo_token;
                            $memo_item->time = $return->time;
                            $memo_item->salesman = $return->salesman;
                            $memo_items[$memo_item->id] = $memo_item; 
                        }

                        $memo_item->returned_items = $return->return_count;
                        $memo_item->return_amount = $return->return_amount;
                    }
                }
                */

                //========== THE NEW WAY ===========//
                $memos = array();
                foreach ($sells as $sell) {
                    $memos[$sell->id]['sell'] = $sell;
                }

                foreach($returns as $return) {
                    $memos[$return->id]['return'] = $return;
                }

                $data['memos'] = $memos;
                //========== THE NEW WAY ===========//


                $data['vars']['sell_data']['type'] = "SHOWING DATA MEMO WISE";
                //$data['vars']['memo_items'] = $memo_items;
                $data['page_name'] = "Sell Information Single day";
                $data['main_content'] = 'showroom/sell_info_single_day';
                $this->load_view($data);

            } else {

                //REQUEST FOR ITEM WISE .
                $sold_items = $this->showroom_model->get_sold_items();
                $returned_items = $this->showroom_model->get_returned_items();
                $discount = $this->showroom_model->get_total_discount();

                $data['vars']['sell_data']['type'] = "SHOWING DATA ITEM WISE";
                $data['vars']['sold_items'] = $sold_items;
                $data['vars']['returned_items'] = $returned_items;
                $data['vars']['discount_total'] = $discount;
                $data['page_name'] = "Sell Information Single day";
                $data['main_content'] = 'showroom/sell_info_single_day';
                $this->load_view($data);
            }
        } else {

            $data['page_name'] = "Sell Information Single day";
            $data['main_content'] = 'showroom/sell_info_single_day';
            $this->load_view($data);

        }
    }




    public function memo($memo_id) {

        $data['vars']['memo_data']['memo_id'] = $memo_id;

        $data['vars']['memo_data']['showroom'] = $this->showroom_model->get_showroom($memo_id);

        $data['vars']['discount'] = $this->showroom_model->get_discount($memo_id);

        $data['vars']['memo_items']['solds'] = $this->showroom_model->memo_detail_sold($memo_id);

        $data['vars']['memo_items']['returns'] = $this->showroom_model->memo_detail_return($memo_id);

        $data['page_name'] = "MEMO ID ". $memo_id;

        $data['main_content'] = 'showroom/memo_detail';

        $this->load_view($data);

    }

}






/*
class MemoItems {

    public $id;

    public $token;

    public $time;

    public $cash_billed=0;

    public $returned_items = 0;

    public $return_amount = 0;

    public $sold_items=0;

    public $discount = 0;

    public $salesman;
}
*/

