<?php

namespace models;

use libraries\Database;

class _Product {

	private $db;
	
	public function __construct() {
		$this->db = new Database();
	}

	public function approve($id) {
		$this->db->query('
		SELECT 
			* 
		FROM 
			`draft_new_product`
		WHERE 
			`id` = :id 
			AND `dp_operatorstamp` = ' . $_SESSION[CLIENT . 'user_id']->id . ' 
			AND `dp_status` = 1
		');

		$this->db->bind(':id', $id, $this->db->PARAM_INT);
		$d = $this->db->single();
		
		$this->db->query('
		INSERT INTO `product` (
			`p_name`,
			`p_description`,
			`p_category`,
			`p_price`,
			`p_brand`,
			`p_model`,
			`p_custom_field`,
			`p_status`,
			`p_sellerstamp`,
			`p_operatorstamp`,
			`p_timestamp`,
			`p_latimestamp`
		) VALUES (
			"' . $d->dp_name . '",
			"' . $d->dp_description . '",
			"' . $d->dp_category . '",
			"' . $d->dp_price . '",
			"' . $d->dp_brand . '",
			"' . $d->dp_model . '",
			"' . addslashes($d->dp_custom_field) . '",
			"' . $d->dp_status . '",
			"' . $d->dp_sellerstamp . '",
			"' . $_SESSION[CLIENT . 'user_id']->id . '",
			"' . time() . '",
			"' . time() . '"
		)
		');

		$this->db->execute();
		$insid = $this->db->lastInsertId();

		// Make approved product dir
		mkdir(DATADIR . DS . 'product' . DS . $insid);

		$files = glob(DATADIR . DS . 'draft-new-product' . DS . $insid . DS . '*');
		foreach ($files as $f) {
			copy($f, DATADIR . DS . 'product' . DS . $insid . DS . basename($f));
		}

		return [
			'status' => 1
		];
	}

	public function reject($id) {
		$this->db->query('
		SELECT 
			* 
		FROM 
			`draft_new_product`
		WHERE 
			`id` = :id 
			AND `dp_operatorstamp` = ' . $_SESSION[CLIENT . 'user_id']->id . ' 
			AND `dp_status` = 1
		');
		$d = $this->db->single();
		$this->db->query('
		INSERT INTO `product` (
			`p_name`,
			`p_description`,
			`p_category`,
			`p_price`,
			`p_brand`,
			`p_model`,
			`p_custom_field`,
			`p_status`,
			`p_sellerstamp`,
			`p_operatorstamp`,
			`p_timestamp`,
			`p_latimestamp`
		) VALUES (
			"' . $d->dp_name . '",
			"' . $d->dp_description . '",
			"' . $d->dp_category . '",
			"' . $d->dp_price . '",
			"' . $d->dp_brand . '",
			"' . $d->dp_model . '",
			"' . $d->dp_custom_field . '",
			"' . $d->dp_status . '",
			"' . $d->dp_sellerstamp . '",
			"' . $_SESSION[CLIENT . 'user_id']->id . '",
			"' . time() . '",
			"' . time() . '"
		)
		');

		$this->db->execute();
		$insid = $this->db->lastInsertId();

		// Make approved product dir
		mkdir(DATADIR . DS . 'product' . DS . $insid . DS . '*');

		$files = glob(DATADIR . DS . 'draft-new-product' . DS . $insid . DS . '*');
		foreach ($files as $f) {
			copy($f, DATADIR . DS . 'product' . DS . $insid . DS . basename($f));
		}

		return [
			'status' => 1
		];
	}

	public function add($data, $file) {

		$validate = $this->exist($data['name'], $data['upc']);
		if ($validate['status'] == 0) {
			return [
				'cardTag' => [
					'type' => 'danger',
					'body' => 'Product already exists <a href="/product/index/'.$validate['id'].'" data-toggle="load-host" data-target="#content">See Here</a>'
				],
				'reset' => true
			];
		}

		$inputValidate = [
			'required' => $data['name'],
			'integer' => $data['upc'],
			'rDecimal' => $data['size'],
			'rInteger' => $data['unit'],
			'rDecimal' => $data['max_price'],
			'decimal' => $data['tax']
		];

		foreach ($inputValidate as $key => $value) {
			if (!validateInput($key, $value)) {
				return [
					'cardTag' => [
						'type' => 'danger',
						'body' => 'Invalid format of input (<code>' . $key . ': ' . $value . '</code>)'
					]
				];
			}
		}

		$product = $this->db->dispense('dop`.`product', false);
		$product->p_name = $data['name'];
		$product->p_upc = $data['upc'];
		$product->p_type = isset($data['type']) ? $data['type'] : 1;
		$product->p_size = $data['size'];
		$product->p_unit = $data['unit'];
		$product->p_max_price = $data['max_price'];
		$product->p_tax = ($data['tax'] == 0 || $data['tax'] == '') ? 0 : ((float) $data['tax'] / 100);
		$product->p_added_by = CLIENT;
		$product->p_status = 1;
		$id = $this->db->store($product);

		if ($file['tmp_name'] != '') {

			if ($file['error'] == 0 && preg_match('/image\/.*/', mime_content_type($file['tmp_name']))) {
				
				$ext = pathinfo($file['name'])['extension'];

				$originalImage = $file['tmp_name'];
				$outputImage = DATADIR . 'product/' . $id . '.jpg';
				$quality = 75;

				$this->db->convertImageToJPG($originalImage, $outputImage, $ext, $quality);

			} else {
				return [
					'cardTag' => [
						'type' => 'danger',
						'body' => 'The file you\'ve uploaded is <b>corrupted</b>'
					]
				];
			}

		}

		return [
			'toast' => [
				'title' => 'Product',
				'body' => 'Added #' . $id
			],
			'reset' => true
		];
	}

	public function list($ord_index, $page, $crit_index) {
		$order = [
			1 => '`id`',
			2 => '`id` DESC',
			3 => '`dp_name`',
			4 => '`dp_name` DESC',
			5 => '`dp_price`',
			6 => '`dp_price` DESC'
		];
		$crit = [
			1 => 'dp_status = 1'
		];
		$this->db->query('
			SELECT 
				`dp`.*, `s`.`id` `sid`, CONCAT(`s`.`s_first_name`, " ", `s`.`s_last_name`) `seller` 
			FROM 
				`draft_new_product` `dp` JOIN `seller` `s` ON (`dp`.`dp_sellerstamp` = `s`.`id`) 
			WHERE 
				' . $crit[$crit_index] . ' AND (dp_operatorstamp = ' . $_SESSION[CLIENT . 'user_id']->id . ' OR dp_operatorstamp IS NULL) 
			ORDER BY 
				' . $order[$ord_index] . ' 
			LIMIT 
				' . ($page * ROW_LIMIT) . ', ' . ROW_LIMIT
			);
		return $this->db->result();
	}

	public function spec($id) {

		$data = [];
		$status = [];

		// Get unassigned draft
		$this->db->query('
			SELECT 
				* 
			FROM 
				`draft_new_product` 
			WHERE 
				`id` = :id 
				AND `dp_status` = 1
				AND (`dp_operatorstamp` IS NULL OR `dp_operatorstamp` = ' . $_SESSION[CLIENT . 'user_id']->id . ')
		');
		$this->db->bind(':id', $id, $this->db->PARAM_INT);
		$ret = $this->db->single();

		if (!isset($ret->id)) {
			$status = [
				'status' => 0,
				'alert' => [
					'type' => 'warning',
					'title' => 'Invalid Id!',
					'body' => 'Refresh this page after a while'
				]
			];
		}

		// Assign draft to operator
		$this->db->query('
		UPDATE 
			`draft_new_product`
		SET 
			`dp_operatorstamp` = ' . $_SESSION[CLIENT . 'user_id']->id . '
		WHERE 
			`id` = ' . $ret->id
		);
		$this->db->execute();

		return ['data' => $ret, 'status' => $status];
	}

	public function nextSpecId() {

		$data = [];
		$status = [];

		$this->db->query('
		SELECT 
			`id`
		FROM 
			`draft_new_product`
		WHERE 
			`dp_operatorstamp` IS NULL
		LIMIT 
			1
		');

		$ret = $this->db->single();
		
		if (!isset($ret->id)) {
			$status = [
				'status' => 0,
				'alert' => [
					'type' => 'secondary',
					'title' => 'No more drafts!',
					'body' => 'Refresh this page after a while'
				]
			];
		}

		return ['data' => $ret, 'status' => $status];
	}

}

?>