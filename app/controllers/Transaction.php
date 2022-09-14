<?php

namespace controllers;

use libraries\Controller;
use models\_Transaction;

class Transaction extends Controller {

	private $t;

	function __construct() {
		$this->t = new _Transaction();
	}

	public function index() {
		$p = $this->get('page', 1);
		$data = $this->t->list($p);
		$this->view('accounts/transaction/index', [
			'data' => $data,
			'page' => $p
		]);
	}

	public function row() {
		$p = $this->get('page', 1);
		$data = $this->t->list($p);
		$this->view('accounts/transaction/row', [
			'data' => $data
		], false);
	}
}

?>