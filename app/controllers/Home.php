<?php

namespace Controllers;

use Libraries\Controller;

class Home extends Controller {
	public function index() {

		$this->view('home', []);

	}
}

?>