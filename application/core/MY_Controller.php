<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

require APPPATH."third_party/MX/Controller.php";

class MY_Controller extends MX_Controller {

	public $site_name = "CMSBlog";
	
	function __construct(){
		parent::__construct();
	}
}
