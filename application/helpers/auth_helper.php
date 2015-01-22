<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 

if (!function_exists('logged_in'))
{
    function logged_in()
    {
    	$CI =& get_instance();
        if(!$CI->ion_auth->is_admin()) {
        	redirect('auth');
        } else {
        	//do nothing. let him FTW
        }
    }
}

if (!function_exists('get_user_id'))
{
    function get_user_id()
    {
        $CI =& get_instance();
        $CI->ion_auth->get_user_id();
    }
}




if (!function_exists('is_staff')) {

    function is_staff() {
    	$CI =& get_instance();
        if(!$CI->ion_auth->in_group('staff' , $CI->ion_auth->get_user_id())) {
        	redirect('auth');
        } else {
        	//do nothing. let him FTW
        }
    }
}


if (!function_exists('get_user')) {
    function get_user() {
        $CI =& get_instance();
        return $CI->ion_auth->user()->row();
    }
}




if (!function_exists('is_member')) {

    function is_member () {
        $CI =& get_instance();
        if(!$CI->ion_auth->in_group('members' , $CI->ion_auth->get_user_id())) {
            redirect('auth');
        } else {
            //do nothing. let him FTW
        }
    }
}


if (!function_exists('is_a_member')) {

    function is_a_member () {
        $CI =& get_instance();
        if(!$CI->ion_auth->in_group('members' , $CI->ion_auth->get_user_id())) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('is_a_staff')) {

    function is_a_staff () {
        $CI =& get_instance();
        if(!$CI->ion_auth->in_group('staff' , $CI->ion_auth->get_user_id())) {
            return false;
        } else {
            return true;
        }
    }
}