<?php

class Backup extends CI_Controller{

	public function __construct(){
		parent::__construct();
	}

	function send(){
        $this->load->library('email');

          $config = array(
			  'protocol' => 'smtp',
			  'smtp_host' => 'ssl://smtp.googlemail.com',
			  'smtp_port' => 465,
			  'smtp_user' => 'echoanik@gmail.com', // change it to yours
			  'smtp_pass' => 'maroon&*5', // change it to yours
			  'mailtype' => 'html',
			  'charset' => 'iso-8859-1',
			  'wordwrap' => TRUE
			);

  $this->load->library('email', $config);
  $this->email->set_newline("\r\n");
  $this->email->from('echoanik@gmail.com'); // change it to yours
  $this->email->to('stackoverflow789@gmail.com'); // change it to yours
  $this->email->subject('Email using Gmail.');
  $this->email->message('Working fine ! !');
  $this->email->attach('/assets/assets.zip');

  if($this->email->send())
 {
  echo 'Email sent.';
 }
 else
{
 show_error($this->email->print_debugger());
}



    }
}