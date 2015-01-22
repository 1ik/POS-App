<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Api extends CI_Controller {



	public function index(){
		
	}

	/**
	*	Takes last transfer id and a storeid
	*	and returns all the transfers which is newer than the transfer id including headoofice to showroom, showroom to showroom and customer change returns.
	*	Deprecated. No longer used by New desktop application software. rather use getNewHeadOfficeToShowroomTransfers
	*/
	public function get_new_items( $showroom_id , $transfer_id = ""){
		$where = "";

		if($transfer_id != ''){
			$where = " where tt.transfer_id > ".$transfer_id;
		}

		$sql = "select tt.transfer_id , count(tt.item_id) as total_items , tt.created_at, tt.from_showroom_id from
				(select i.id as item_id, t.id as transfer_id , t.created_at, t.from_showroom_id from transfer t
				 	left join transferred_item ti on ti.transfer_id = t.id
					left join item i on i.id = ti.item_id
				 WHERE i.showroom_id = ".$showroom_id." and t.to_showroom_id = ".$showroom_id." and i.id not in (select item_id from sold_item)

				order by t.id desc) tt

				INNER JOIN(
				    SELECT i.id as item_id , MAX(tf.id) as max_transfer from transfer tf
				    left join transferred_item ti on ti.transfer_id = tf.id
				    left join item i on i.id = ti.item_id
				    
				    where i.showroom_id = ".$showroom_id." AND i.id not in (SELECT item_id from sold_item) AND tf.to_showroom_id = ".$showroom_id."
				    group by item_id
				) ht on tt.item_id = ht.item_id AND tt.transfer_id = ht.max_transfer
				".$where."
				group by tt.transfer_id
				order by tt.transfer_id desc";


		$query = $this->db->query($sql);
		$data['transfer_ids'] = $query->result();
		$sql = "select tt.showroom_id, tt.transfer_id, tt.item_id, tt.size, tt.type, tt.sell_price, tt.color_code from 
			(select 
             i.id as item_id, 
             t.id as transfer_id,
             s.name as size,
             ty.name as type,
             i.color_code,
             i.showroom_id,
             i.sell_price
             from transfer t
			 	left join transferred_item ti on ti.transfer_id = t.id
				left join item i on i.id = ti.item_id
             	left join size s on i.size_id = s.id
             	left join item_type ty on ty.id = s.item_type_id
			 WHERE i.showroom_id = ".$showroom_id." and t.to_showroom_id = ".$showroom_id." and i.id not in (select item_id from sold_item)

			order by t.id desc) tt

			INNER JOIN(
			    SELECT i.id as item_id , MAX(tf.id) as max_transfer from transfer tf
			    left join transferred_item ti on ti.transfer_id = tf.id
			    left join item i on i.id = ti.item_id
			    
			    where i.showroom_id = ".$showroom_id." AND i.id not in (SELECT item_id from sold_item) AND tf.to_showroom_id = ".$showroom_id."
			    group by item_id
			) ht on tt.item_id = ht.item_id AND tt.transfer_id = ht.max_transfer ".$where;
		
		$query = $this->db->query($sql);
		$data["items"] = $query->result();

		echo json_encode($data);
	}


	/**
	*	Takes last transfer id and a storeid
	*	and returns all the transfers which is newer than the transfer id. :)
	*	Gets the transfers only from Headoffice to Showroom
	*/
	public function getNewHeadOfficeToShowroomTransfers( $showroom_id , $transfer_id = ""){

		//$where = "AND item.id not in (SELECT item_id from sold_item) AND item.showroom_id = ".$showroom_id;

		$where = "";

		if($transfer_id != ''){

			$where = " where tt.transfer_id > ".$transfer_id;

		}


		$sql = "select tt.transfer_id, tt.contextual_transfer_id, count(tt.item_id) as total_items , tt.created_at, tt.from_showroom_id from
				(select i.id as item_id, t.id as transfer_id , t.created_at, t.from_showroom_id,
				hot.id as contextual_transfer_id  from transfer t
				 	left join transferred_item ti on ti.transfer_id = t.id
				 	left join ho_showroom_transfers hot on hot.transfer_id = t.id
					left join item i on i.id = ti.item_id
				 	WHERE i.showroom_id = ".$showroom_id." 
				 			and t.to_showroom_id = ".$showroom_id." 
				 			and i.id not in (select item_id from sold_item)
				 			and t.from_showroom_id = 1 
				order by t.id desc) tt
				INNER JOIN(
				    SELECT i.id as item_id , MAX(tf.id) as max_transfer from transfer tf
				    left join transferred_item ti on ti.transfer_id = tf.id
				    left join item i on i.id = ti.item_id
				    where i.showroom_id = ".$showroom_id." AND i.id not in (SELECT item_id from sold_item) AND tf.to_showroom_id = ".$showroom_id."
				    group by item_id
				) ht on tt.item_id = ht.item_id AND tt.transfer_id = ht.max_transfer
				".$where."
				group by tt.transfer_id
				order by tt.transfer_id desc";

		$query = $this->db->query($sql);
		$data['transfer_ids'] = $query->result();

		$sql = "select tt.showroom_id, tt.transfer_id, tt.item_id, tt.size, tt.type, tt.designer_style, tt.sell_price, tt.color_code, tt.contextual_transfer_id from 
			(select 
             i.id as item_id, 
             t.id as transfer_id,

             s.name as size,

             ty.name as type,

             i.color_code,

             i.showroom_id,

             i.designer_style,

             i.sell_price,

             hot.id as contextual_transfer_id

             from transfer t

			 	left join transferred_item ti on ti.transfer_id = t.id

				left join item i on i.id = ti.item_id

             	left join size s on i.size_id = s.id
             	left join ho_showroom_transfers hot on hot.transfer_id = t.id

             	left join item_type ty on ty.id = s.item_type_id

			 WHERE i.showroom_id = ".$showroom_id." and 
			 t.to_showroom_id = ".$showroom_id." 
			 and t.from_showroom_id = 1 
			 and i.id not in (select item_id from sold_item)


			order by t.id desc) tt



			INNER JOIN(

			    SELECT i.id as item_id , MAX(tf.id) as max_transfer from transfer tf

			    left join transferred_item ti on ti.transfer_id = tf.id

			    left join item i on i.id = ti.item_id

			    

			    where i.showroom_id = ".$showroom_id." AND i.id not in (SELECT item_id from sold_item) AND tf.to_showroom_id = ".$showroom_id."

			    group by item_id

			) ht on tt.item_id = ht.item_id AND tt.transfer_id = ht.max_transfer ".$where;

		

		$query = $this->db->query($sql);

		$data["items"] = $query->result();



		echo json_encode($data);

	}





	/**
	*	Takes last transfer id and a storeid
	*	and returns all the transfers which is newer than the transfer id. :)
	*	Gets the transfers only the returned items from customers which is from_showroom_id = to_showroom_id
	*/

	public function get_new_returned_items( $showroom_id , $transfer_id = ""){

		//$where = "AND item.id not in (SELECT item_id from sold_item) AND item.showroom_id = ".$showroom_id;

		$where = "";

		if($transfer_id != ''){

			$where = " where tt.transfer_id > ".$transfer_id;

		}


		$sql = "select tt.transfer_id, tt.contextual_transfer_id, count(tt.item_id) as total_items , tt.created_at, tt.designer_style, tt.from_showroom_id from

				(select i.id as item_id, t.id as transfer_id , t.created_at, t.from_showroom_id, i.designer_style,
					cot.id as contextual_transfer_id  from transfer t

				 	left join transferred_item ti on ti.transfer_id = t.id
				 	left join customer_return_transfers cot on cot.transfer_id = t.id

					left join item i on i.id = ti.item_id

				 	WHERE i.showroom_id = ".$showroom_id." 
				 			and t.to_showroom_id = ".$showroom_id." 
				 			and i.id not in (select item_id from sold_item)
				 			and t.from_showroom_id = t.to_showroom_id 
				order by t.id desc) tt

				INNER JOIN(

				    SELECT i.id as item_id , MAX(tf.id) as max_transfer from transfer tf

				    left join transferred_item ti on ti.transfer_id = tf.id

				    left join item i on i.id = ti.item_id

				    

				    where i.showroom_id = ".$showroom_id." AND i.id not in (SELECT item_id from sold_item) AND tf.to_showroom_id = ".$showroom_id."

				    group by item_id

				) ht on tt.item_id = ht.item_id AND tt.transfer_id = ht.max_transfer

				".$where."

				group by tt.transfer_id

				order by tt.transfer_id desc";



		$query = $this->db->query($sql);

		$data['transfer_ids'] = $query->result();



		$sql = "select tt.showroom_id, tt.designer_style, tt.transfer_id, tt.item_id, tt.size, tt.type, tt.sell_price, tt.color_code, tt.contextual_transfer_id from 

			(select 

             i.id as item_id, 
             t.id as transfer_id,
             s.name as size,
             ty.name as type,
             i.color_code,
             i.showroom_id,
             i.designer_style,
             i.sell_price,
             crt.id as contextual_transfer_id

             from transfer t

			 	left join transferred_item ti on ti.transfer_id = t.id

				left join item i on i.id = ti.item_id

             	left join size s on i.size_id = s.id

             	left join item_type ty on ty.id = s.item_type_id
             	left join customer_return_transfers crt on crt.transfer_id = t.id

			 WHERE i.showroom_id = ".$showroom_id." and t.to_showroom_id = ".$showroom_id." 
			 and i.id not in (select item_id from sold_item)
			 and t.from_showroom_id = t.to_showroom_id 


			order by t.id desc) tt



			INNER JOIN(

			    SELECT i.id as item_id , MAX(tf.id) as max_transfer from transfer tf

			    left join transferred_item ti on ti.transfer_id = tf.id

			    left join item i on i.id = ti.item_id

			    

			    where i.showroom_id = ".$showroom_id." AND i.id not in (SELECT item_id from sold_item) AND tf.to_showroom_id = ".$showroom_id."

			    group by item_id

			) ht on tt.item_id = ht.item_id AND tt.transfer_id = ht.max_transfer ".$where;

		

		$query = $this->db->query($sql);

		$data["items"] = $query->result();



		echo json_encode($data);

	}





	/**
	*	Takes last transfer id and a storeid
	*	and returns all the transfers which is newer than the transfer id. :)
	*	Gets the transfers only the items which have been transferred from other showroom but not form Headoffice
	*/

	public function get_new_showroom_transferred_items( $showroom_id , $transfer_id = ""){

		//$where = "AND item.id not in (SELECT item_id from sold_item) AND item.showroom_id = ".$showroom_id;

		$where = "";

		if($transfer_id != ''){

			$where = " where tt.transfer_id > ".$transfer_id;

		}


		$sql = "select tt.transfer_id, tt.contextual_transfer_id, count(tt.item_id) as total_items , tt.created_at, tt.from_showroom_id from

				(select i.id as item_id, t.id as transfer_id , t.created_at, t.from_showroom_id,
				sot.id as contextual_transfer_id  from transfer t

				 	left join transferred_item ti on ti.transfer_id = t.id
				 	left join showroom_to_showroom_transfers sot on sot.transfer_id = t.id

					left join item i on i.id = ti.item_id

				 	WHERE i.showroom_id = ".$showroom_id." 
				 			and t.to_showroom_id = ".$showroom_id." 
				 			and i.id not in (select item_id from sold_item)
				 			and t.from_showroom_id != t.to_showroom_id 
				 			and t.from_showroom_id != 1
				order by t.id desc) tt

				INNER JOIN(

				    SELECT i.id as item_id , MAX(tf.id) as max_transfer from transfer tf

				    left join transferred_item ti on ti.transfer_id = tf.id

				    left join item i on i.id = ti.item_id

				    

				    where i.showroom_id = ".$showroom_id." AND i.id not in (SELECT item_id from sold_item) AND tf.to_showroom_id = ".$showroom_id."

				    group by item_id

				) ht on tt.item_id = ht.item_id AND tt.transfer_id = ht.max_transfer

				".$where."

				group by tt.transfer_id

				order by tt.transfer_id desc";



		$query = $this->db->query($sql);

		$data['transfer_ids'] = $query->result();



		$sql = "select tt.showroom_id, tt.transfer_id, tt.designer_style, tt.item_id, tt.size, tt.type, tt.sell_price, tt.color_code from 

			(select 
             i.id as item_id, 
             t.id as transfer_id,
             s.name as size,
             ty.name as type,
             i.color_code,
             i.showroom_id,
             i.designer_style,
             i.sell_price
             from transfer t
			 	left join transferred_item ti on ti.transfer_id = t.id
				left join item i on i.id = ti.item_id
             	left join size s on i.size_id = s.id
             	left join item_type ty on ty.id = s.item_type_id
			 WHERE i.showroom_id = ".$showroom_id." and 
			 t.to_showroom_id = ".$showroom_id." and 
			 i.id not in (select item_id from sold_item)
			 and t.from_showroom_id != t.to_showroom_id 
			 and t.from_showroom_id != 1

			order by t.id desc) tt
			INNER JOIN(
			    SELECT i.id as item_id , MAX(tf.id) as max_transfer from transfer tf
			    left join transferred_item ti on ti.transfer_id = tf.id
			    left join item i on i.id = ti.item_id
			    where i.showroom_id = ".$showroom_id." AND i.id not in (SELECT item_id from sold_item) AND tf.to_showroom_id = ".$showroom_id."
			    group by item_id

			) ht on tt.item_id = ht.item_id AND tt.transfer_id = ht.max_transfer ".$where;
		
		$query = $this->db->query($sql);
		$data["items"] = $query->result();
		echo json_encode($data);

	}



	/**
	* receives post request for a new memo(s) in json format. and updates database.
	*/
	public function request_new_memo() {

		if($this->input->post()){

			$json_string = $this->input->post("memos");

			$memos = json_decode($json_string , true);

			$memoids = array();
			$data = array();
			$sold_item = array();
			$returned_item_data = array();
			$showroom_id = '';



			foreach($memos as $memo) {

				$memoids[] = $memo['id'];


				//we are checking if the request was already made.
				$this->db->select('*');
				$this->db->where('token', $memo['token']);
				$query = $this->db->get('memo');

				if($query->num_rows() > 0) {
					//yes the memo was inserted in the past.
					//we don't want to insert it again.
					continue;
				}
				

				//make a new memo in the memo table.
				$this->db->insert('memo' , array('added_on' => $memo['added_on'] , 'showroom_id' => $memo['showroom_id'] , 'discount' => $memo['discount'], 'token' => $memo['token'], 'salesman' => $memo['salesman']));

				$showroom_id = $memo['showroom_id'];

				//we get the id of the memot table.
				$memo_id = $this->db->insert_id();

				//do we have any sold items ??

				if($memo['items'] != '') {

					//yes we have .

					$items = explode("," , $memo['items']);

					foreach($items as $item_id){

						$data[] = array('item_id' => $item_id , 'memo_id' => $memo_id);

						$sold_item[] = array('item_id' => $item_id);
					}

				}



				//do we have any returned item ?

				if($memo['changed_items'] != "" ) {

					$returned_items = explode("," , $memo['changed_items']);

					if( count($returned_items) > 0) {

						//yes we have returned item.

						foreach ($returned_items as $returned_item) {

							$returned_item_data[] = array('memo_id' => $memo_id, 'item_id' => $returned_item);

						}

						$sql = "DELETE from sold_item where item_id IN ( ".$memo['changed_items'].")";

						$this->db->query($sql);

					}	

				}

			}


			//do we have atleast one memo that contains sold product??

			if(count($data) > 0) {
				$this->db->insert_batch('memo_item' , $data);
				$this->db->insert_batch('sold_item' , $sold_item);
			}

			if(count($returned_item_data) > 0) {

				$this->db->insert_batch('returned_item' , $returned_item_data);

				$this->transfer_change_items($returned_item_data, $showroom_id);

			}

			echo json_encode($memoids);
		}
	}






	/**
	* The previous function request_new_memos() only returned the ids of new memo sent from desktop app.
	* now it sends the newly inserted ids of memo in the server database.
	*/
	public function submit_new_memos() {

		if($this->input->post()) {

			$this->db->trans_start();

			$json_string = $this->input->post("memos");

			$memos = json_decode($json_string , true);

			$memoids = array();
			$data = array();
			$sold_item = array();
			$returned_item_data = array();
			$showroom_id = '';



			foreach($memos as $memo) {

				//$memoids[] = $memo['id'];


				//we are checking if the request was already made.
				$this->db->select('*');
				$this->db->where('token', $memo['token']);
				$query = $this->db->get('memo');
				if($query->num_rows() > 0) {
					//yes the memo was inserted in the past.
					//we don't want to insert it again.
					$result = $query->result();
					$result = $result[0];
					$memoids[] = array('id' => $memo['id'], 'memo_id' => $result->id);
					continue;
				}
				

				//make a new memo in the memo table.
				$this->db->insert('memo' , array('added_on' => $memo['added_on'] , 'showroom_id' => $memo['showroom_id'] , 'discount' => $memo['discount'], 'token' => $memo['token'], 'salesman' => $memo['salesman']));

				$showroom_id = $memo['showroom_id'];

				//we get the id of the memot table.
				$memo_id = $this->db->insert_id();
				$memoids[] = array('id' => $memo['id'], 'memo_id' => $memo_id); //we are sending the id of newly inserted memo so that the local application mark the memo as updated instead of deleting.

				//do we have any sold items ??

				if($memo['items'] != '') {

					//yes we have .

					$items = explode("," , $memo['items']);

					foreach($items as $item_id){

						$data[] = array('item_id' => $item_id , 'memo_id' => $memo_id);

						$sold_item[] = array('item_id' => $item_id);
					}

				}



				//do we have any returned item ?

				if($memo['changed_items'] != "" ) {

					$returned_items = explode("," , $memo['changed_items']);

					if( count($returned_items) > 0) {

						//yes we have returned item.

						foreach ($returned_items as $returned_item) {

							$returned_item_data[] = array('memo_id' => $memo_id, 'item_id' => $returned_item);

						}

						$sql = "DELETE from sold_item where item_id IN ( ".$memo['changed_items'].")";

						$this->db->query($sql);

					}	

				}

			}


			//do we have atleast one memo that contains sold product??

			if(count($data) > 0) {
				$this->db->insert_batch('memo_item' , $data);
				$this->db->insert_batch('sold_item' , $sold_item);
			}

			if(count($returned_item_data) > 0) {

				$this->db->insert_batch('returned_item' , $returned_item_data);

				$this->transfer_change_items($returned_item_data, $showroom_id);

			}

			$this->db->trans_complete();
			echo json_encode($memoids);
		}
	}























	private function transfer_change_items($returned_item_datas , $showroom_id){

		$this->db->trans_start();


		$from_showroom_id		= $showroom_id;
		$to_showroom_id			= $showroom_id;

		$date = new DateTime();
		$this->db->insert('transfer' , array('from_showroom_id' => $from_showroom_id, 'to_showroom_id' => $to_showroom_id , 'created_at' => $date->format('Y-m-d H:i:s') ));

		//get a new transfer id.
		$transfer_id = $this->db->insert_id();

		$values = '';

		$i=0;

		foreach($returned_item_datas as $returned_item_data){

            if($returned_item_data != null){

                $transferred_items[] = array('transfer_id' => $transfer_id , 'item_id' => $returned_item_data['item_id']);

                $values = $values." (".$returned_item_data['item_id'].",".$to_showroom_id.") ";

                if($i < count($returned_item_datas)-1){

                    $values = $values . ","; //append comma only after the prior rows to the LAST one.
                }
            }
            $i++;
        }


        //update each item's showroom id. (current location)
        $updates = "INSERT INTO item( id, showroom_id ) VALUES".$values." ON DUPLICATE KEY UPDATE showroom_id = VALUES(showroom_id)";

		// insert transfer items.
        $this->db->insert_batch('transferred_item' , $transferred_items);

        //then update each item's current location(showroom_id)
        $this->db->query($updates);


        //add this transfer_id to customer_return_transfers table for contextual_transfer_id.
        $this->db->insert('customer_return_transfers' , array('transfer_id' => $transfer_id));

        //done
        $this->db->trans_complete();

	}



	/**
	* This function is used for transferring items from one showroom to another showroom.
	* It sends an array of transfers.
	* Used only by the desktop clients.
	*/

	public function transfer_to_showroom() {

		$this->db->trans_start();


		if($this->input->post()){

			$outputs = array();



			$transfers_json = $this->input->post("transfers"); //json array

			$transfers = json_decode($transfers_json); //array of transfers



			$transferred_items = array();

			$mainval = '';



			foreach($transfers as $transfer) {

				$outputs[] = $transfer->id;



				$from_showroom_id		= $transfer->from_showroom_id;

				$to_showroom_id			= $transfer->to_showroom_id;

				$items 					= explode("," , $transfer->items);

				$date = new DateTime();

				$this->db->insert('transfer' , array('from_showroom_id' => $from_showroom_id, 'to_showroom_id' => $to_showroom_id , 'created_at' => $date->format('Y-m-d H:i:s') ));

				$transfer_id = $this->db->insert_id();
				
				//insert this id to showroom_to_showroom_transfers table.
				$this->db->insert('showroom_to_showroom_transfers', array('transfer_id' => $transfer_id));								

				$values = '';

				$i=0;

				foreach($items as $item_id){

		            if($item_id != null){

		                $transferred_items[] = array('transfer_id' => $transfer_id , 'item_id' => $item_id);

		                $values = $values." (".$item_id.",".$to_showroom_id.") ";

		                if($i < count($items)-1){

		                    $values = $values . ","; //append comma only after the prior rows to the LAST one.

		                }

		            }

		            $i++;

		        }



		        if($mainval != '') {

		        	$mainval .= ' , ';

		        }



		        $mainval .= $values;

			}



			$updates = "INSERT INTO item( id, showroom_id ) VALUES".$mainval." ON DUPLICATE KEY UPDATE showroom_id = VALUES(showroom_id)";



			// insert transferitems.
	        $this->db->insert_batch('transferred_item' , $transferred_items);

	        //then update each item's current location(showroom_id)
	        $this->db->query($updates);

	        //doneP

	        $this->db->trans_complete();

	        echo json_encode($outputs);
		}

	}





	/**
	* Receives a json array containing one or more expense information. Takes it and parse it and insert into the db.
	*/

	public function request_new_expense(){

		if($this->input->post()){

			$this->db->trans_start();

			$expense_informations = $this->input->post("expenses");

			$expenses = json_decode($expense_informations , true);

			$data = array();

			$expense_ids = array();

			foreach ($expenses as $expense) {

				$expense_ids[] = $expense['expense_id'];

				unset($expense['expense_id']);

				$data[] = $expense;

			}



			$this->db->insert_batch('expense' , $data);

			$this->db->trans_complete();
			echo json_encode($expense_ids);

		}



	}



	public function get_expense_types(){

		$this->load->model("expense_type_model");

		$expense_types = $this->expense_type_model->get(array('id' , 'reason'));

		echo json_encode($expense_types);

	}



	





	/**
	* Receives json data from Java desktop client. and serves
	* Depricated
	*/
	public function return_items_to_head_office(){

		$this->load->model('transfer_model');

        $json_data = $this->input->post('transfers');

        $this->transfer_model->add($json_data);

        echo 'ok';

    }





    public function get_showrooms_list(){

    	$this->load->model('showroom_model');

    	$showrooms = $this->showroom_model->get(array('id' , 'name' , 'location'));

    	echo json_encode($showrooms);

    }


    /**
    *	returns the vat and registrations number of a showroom
    */
    public function showroom_vat_reg($showroom_id = '') {
    	if($showroom_id == '') {
    		return;
    	}

    	$this->load->model("showroom_model");
    	$vat_reg = $this->showroom_model->get(array('vat_reg') , array('id' => $showroom_id));
    	echo json_encode($vat_reg);
    }





    public function check_sold_item($item_id) {

    	$sql = "SELECT item.id as item_id, item_type.name as item_type, size.name as item_size,

				item.sell_price as price, item.color_code as color , showroom.name as sell_showroom, DATE_FORMAT(memo.added_on , '%d-%m-%y') as sell_date

				FROM `memo_item` 

				join memo on memo.id = memo_item.memo_id

				join item on item.id = memo_item.item_id

				join size on size.id = item.size_id

				join item_type on size.item_type_id = item_type.id

				join showroom on memo.showroom_id = showroom.id

				where $item_id IN (SELECT item_id from sold_item ) AND item.id = ".$item_id;

		$query = $this->db->query($sql);

		if($query->num_rows() > 0) {

			$result = $query->result_array();

			echo json_encode($result[0]);

		} else {
			echo "" ;
		}

    }

    public function get_discounts(){
    	$sql = "SELECT distinct(item.designer_style) as designer_style, percent FROM `item` left outer join discounts on discounts.designer_style = item.designer_style order by designer_style asc";
        $query = $this->db->query($sql);
        $result = $query->result();
        echo json_encode($result);
    }



    public function desktop_app_launch_info () {

    	//get the user's names



    	//get he domain names

    }



    public function get_user_pass() {

    	

    }



}



/* End of file api.php */

/* Location: ./application/controllers/api.php */