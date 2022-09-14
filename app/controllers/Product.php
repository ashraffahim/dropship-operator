<?php

namespace controllers;

use libraries\Controller;
use models\_Product;

class Product extends Controller {

	private $product;
	
	function __construct() {
		$this->product = new _Product();
	}

	public function row($o = 1, $c = 1) {
		$p = $this->get('page', 0);
		$data = $this->product->list($o, $p, $c);
		$this->view('product/row', [
			'data' => $data,
			'page' => $p,
			'o' => $o,
			'c' => $c
		]);
	}

	public function index($o = 1, $c = 1) {
		$p = $this->get('page', 0);
		$data = $this->product->list($o, $p, $c);
		$this->view('product/index', [
			'data' => $data,
			'page' => $p,
			'o' => $o,
			'c' => $c
		]);
	}

	public function approve() {
		if ($this->RMisPost()) {
			$this->sanitizeInputPost();
			$status = $this->product->approve($_POST['id']);
			$this->status($status);
		}
	}

	public function reject() {
		if ($this->RMisPost()) {
			$this->sanitizeInputPost();
			$status = $this->product->reject($_POST['id']);
			$this->status($status);
		}
	}

	public function spec($id) {
		$data = [];
		if ($id == 'next') {
			$data = $this->product->nextSpecId();
			if (isset($data['data']->id)) {
				$id = $data['data']->id;
				$data = $this->product->spec($id);
			}
		} else {
			$data = $this->product->spec($id);
		}
		$this->view('product/spec', $data);
	}

}

?>