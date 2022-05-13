<?php

namespace controllers;

use libraries\Controller;
use models\_Product;

class Product extends Controller {

	private $product;
	private $productType;
	
	function __construct() {
		$this->product = new _Product();
		$this->productType = [
			(object) [
				'data' => 'Product<br><small>Trading with physically existing entity</small>',
				'val' => 1
			],
			(object) [
				'data' => 'Service<br><small>Trading labour</small>',
				'val' => 2
			]
		];
	}

	public function row($o = 1, $c = 1) {
		$p = $this->get('page');
		$data = $this->product->list($o, $p, $c);
		$this->view('product/row', [
			'data' => $data,
			'page' => $p,
			'o' => $o,
			'c' => $c
		]);
	}

	public function index($o = 1, $c = 1) {
		$p = $this->get('page');
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