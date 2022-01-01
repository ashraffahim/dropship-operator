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

	public function approve($id = false) {
		if ($this->isPageChange()) {
			$this->sanitizeInputGet();
			$data = $this->product->pendingApprovalList($this->getTableRowOrder(), $this->getLoadPage());
			$this->view('product/row', ['data' => $data]);
		} else {
			if ($id) {
				$data = $this->product->spec($id);
				$this->view('product/spec', [
					'data' => $data
				]);
			} else {
				$data = $this->product->pendingApprovalList($this->getTableRowOrder(), $this->getLoadPage());
				$this->view('product/index', [
					'id' => $id,
					'ord' => $this->getTableRowOrder(),
					'page' => $this->getLoadPage(),
					'data' => $data
				]);
			}
		}
	}

	public function add() {

		if ($this->RMisPost()) {
			$this->sanitizeInputPost();
			$_POST['added_by'] = $_SESSION[CLIENT . 'user_id']->id;
			$data = $this->product->add($_POST, $_FILES['image']);
			$this->status($data);
		}

		$unitOption = $this->product->unitOption();
		$this->view('product/add', [
			'unit_option' => $unitOption,
			'product_type' => $this->productType
		]);
	}
}

?>