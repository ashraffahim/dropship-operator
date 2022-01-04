<?php

namespace controllers;

use libraries\Controller;

class Home extends Controller {
	public function index() {

		$this->view('home', []);

	}
}

?>