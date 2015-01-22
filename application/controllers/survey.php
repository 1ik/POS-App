<?php

class Survey extends CI_Controller {


    function __construct(){
        parent::__construct();
        
    }

    function load_view(&$data){

        

        //$data['links'][] = anchor('showroom/sell_info' , "Sales information ");
		
		$this->load->view('template' , $data);
    }


    /**
    * =================================================================================
    * Shows the survey form.
    * =================================================================================
    */
    public function index() {
    
    	$this->load->model('showroom_model');
    	$data['active'] = 'survey';
    	$data['page_name'] = 'Item Survey';
        $data['vars']['showrooms'] = $this->showroom_model->get();
        $data['main_content'] = 'survey/index';

        $this->load_view($data);
    }




    public function get_items($showroom_id, $survey_id = '') {
    	//get all the items currently in the showroom.

    	if($survey_id != '') {
    		$sql = "SELECT 
			    i.id,
			    i.color_code,
			    i.designer_style,
			    i.sell_price,
			    s.name as size,
			    t.name as type
			    FROM `item` i
			    left outer join size s on s.id = i.size_id
			    left outer join item_type t on t.id = s.id
                join survey_items_not_found sif on sif.item_id = i.id
                where sif.survey_id = $survey_id";

            $query = $this->db->query($sql);
    		$items = $query->result();

    		$sql = "select total_items from surveys where id = ".$survey_id;
    		$query = $this->db->query($sql);
    		$result = $query->result();
    		$row = $result[0];

    		$data = array('survey_id' => $survey_id, 'items' => $items, 'total_items' => $row->total_items);
    		echo json_encode($data);
    		die();
    	}






    	$sql = "SELECT 
			    i.id,
			    i.color_code,
			    i.designer_style,
			    i.sell_price,
			    s.name as size,
			    t.name as type
			    FROM `item` i
			    left outer join size s on s.id = i.size_id
			    left outer join item_type t on t.id = s.id
			 WHERE i.id not in (select * from sold_item) and showroom_id = $showroom_id";
    	$query = $this->db->query($sql);
    	$items = $query->result();

    	
    	$survey_data = array(
    			'user_id' => $this->ion_auth->get_user_id(),
    			'showroom_id' => $showroom_id,
    			'total_items' => count($items)
    		);
    	//open a new survey_id
    	$this->db->insert("surveys", $survey_data);


    	$survey_id = $this->db->insert_id();
    	$items_not_found = array();

    	foreach ($items as $item) {
    		$items_not_found[] = array(
    				'item_id' => $item->id,
    				'survey_id' => $survey_id
    			);
    	}

    	//initially all the items will be not found of this survey.
    	$this->db->insert_batch('survey_items_not_found', $items_not_found);

    	// echo count($result);
    	// die();
    	$data = array('survey_id' => $survey_id, 'items' => $items);
    	echo json_encode($data);
    }


    public function accept_item () {
    	$s_id = $this->input->post('survey_id' , true);
    	$i_id = $this->input->post('item_id' , true);


    	//remove the item from not found list.
    	$where = array('item_id' => $i_id, 'survey_id' => $s_id);
    	$this->db->delete('survey_items_not_found', $where);

    	echo 'ok';
    }



    public function continue_survey($survey_id) {
    	echo $survey_id;
    }



    /**
    * Survey data is submitted.
    */
    public function new_survey() {
    	$report = $this->input->post('val', true);
    	$data = json_decode($report);
  
    	$this->db->where('id', $data->survey_id);
    	$this->db->update('surveys', array('finished' => 1));
    	echo 'ok';
    }

    

    public function survey_reports() {

    	$sql = "SELECT 
			     s.id as survey_id, 
			     s.total_items,
			     s.created_at,
			     s.finished,
			     s.showroom_id, 
			     CONCAT(u.first_name,' ', u.last_name) as user, 
			     sh.name as showroom,
			     k.missing_items FROM surveys s
				join showroom sh on s.showroom_id = sh.id
				join users u on u.id = s.user_id
				left outer join 
				(select survey_id, count(*) as missing_items from survey_items_not_found si group by si.survey_id ) k on k.survey_id = s.id
				group by s.id";
		$result = $this->db->query($sql);

		$data['vars']['surveys'] = $result->result();
		$data['page_name'] = 'Survey Reports';
		$data['active'] = 'survey_reports';
		$data['main_content'] = 'survey/survey_reports';
		$this->load_view($data);
    }


    public function report_print($survey_id) {
	    $sql = "SELECT 
				     s.id as survey_id, 
				     s.created_at, 
				     s.total_items,
				     s.showroom_id, 
				     CONCAT(u.first_name,' ', u.last_name) as user, 
				     sh.name as showroom,

				     k.missing_items FROM surveys s
					join showroom sh on s.showroom_id = sh.id
					join users u on u.id = s.user_id
					left outer join 
					(select survey_id, count(*) as missing_items from survey_items_not_found si group by si.survey_id ) k on k.survey_id = s.id

					where s.id = $survey_id
					group by s.id";


		$result = $this->db->query($sql);
		$result = $result->result();

		$data['survey_data'] = $result[0];

		$sql = "SELECT sh.name as showroom FROM `surveys` s join showroom sh on sh.id = s.showroom_id
					where s.id = ".$survey_id;
		$query = $this->db->query($sql);
		$result = $query->result();
		$result = $result[0];
		$data['showroom'] = $result->showroom;

		$sql = "SELECT 
					i.id,
				    i.designer_style,
				    i.color_code,
				    size.name as size,
				    it.name as type,
				    i.sell_price
				FROM `survey_items_not_found` si 
				join item i on i.id = si.item_id
				join size on i.size_id = size.id
				join item_type it on it.id = size.item_type_id
				where si.survey_id = $survey_id";
		$result = $this->db->query($sql);
		$data['missing_items'] = $result->result();
		$data['main_content'] = 'survey/report_print';
		$this->load->view('survey/report_print', $data);
    }



    /**
    * Returns a showroom id against the survey_id
    */
    public function survey_showroom_id ($survey_id) {
    	$this->db->select('showroom_id');
    	$this->db->where(array('id'=> $survey_id));
    	$query = $this->db->get('surveys');
    	$result = $query->result();
    	if($query->num_rows() > 0 ) {
    		$row = $result[0];
    		echo $row->showroom_id;
    		die();
    	}
    	echo -1;
    }

}





