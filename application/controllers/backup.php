<?php

class Backup extends CI_Controller{

	public function __construct(){
		parent::__construct();
	}

	public function send() {
		$this->load->library('email');
		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'jhinuk.backup@gmail.com', // change it to yours
			'smtp_pass' => 'jhinuk&*backUp@', // change it to yours
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE);

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('jhinuk.backup@gmail.com'); // change it to yours
  		$this->email->to('jhinuk.backup@gmail.com'); // change it to yours
  		$this->email->subject('Backup');
  		$this->email->message('Backup of jhinukfashion');

  		//attach the file
  		//$file =  './uploads/info.txt';
  		$file = '../../backups/dbbackup.sql';
  		$this->email->attach($file);

  		if($this->email->send()) {
  			echo 'Email sent.';
  		} else {
  			// :-(
  			show_error($this->email->print_debugger());
  		}
	}


	public function run() {
		exec('mysqldump --user=jhinukfa_user --password=I(6kAP{dsvvV --host=localhost jhinukfa_db > /home/jhinukfa/backups/jhinukfa_db.sql');
	}




}