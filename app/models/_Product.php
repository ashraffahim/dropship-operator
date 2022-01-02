<?php

namespace models;

use libraries\Database;

class _Product {

	private $db;
	
	public function __construct() {
		$this->db = new Database();
	}

	public function unitOption() {
		$this->db->query('SELECT `id` AS `val`, `pu_title` AS `data` FROM `dop`.`product_unit` WHERE `pu_status` = 1');
		return $this->db->result();
	}

	public function exist($name, $upc) {
		$this->db->query('SELECT COUNT(`id`) AS `status`, `id` FROM `dop`.`view_product` WHERE `name` = :name OR `upc` = :upc');
		$this->db->bind(':name', $name, $this->db->PARAM_STR);
		$this->db->bind(':upc', $upc, $this->db->PARAM_INT);
		$product = $this->db->single();
		return [
			'status' => !$product->status,
			'id' => $product->id
		];
	}

	public function add($data, $file) {

		$validate = $this->exist($data['name'], $data['upc']);
		if ($validate['status'] == 0) {
			return [
				'cardTag' => [
					'type' => 'danger',
					'body' => 'Product already exists <a href="/Product/index/'.$validate['id'].'" data-toggle="load-host" data-target="#content">See Here</a>'
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
				$outputImage = DATA . 'product/' . $id . '.jpg';
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

	public function pendingApprovalList($ord_index, $page) {
		$order = [
			1 => '`id`',
			2 => '`id` DESC',
			3 => '`dp_name`',
			4 => '`dp_name` DESC',
			5 => '`dp_price`',
			6 => '`dp_price` DESC'
		];
		$this->db->query('SELECT `dp`.*, `s`.`id` `sid`, CONCAT(`s`.`s_first_name`, " ", `s`.`s_last_name`) `seller` FROM `draft_product` `dp` JOIN `seller` `s` ON (`dp`.`dp_sellerstamp` = `s`.`id`) ORDER BY ' . $order[$ord_index] . ' LIMIT ' . ($page * ROW_LIMIT) . ', ' . ROW_LIMIT);
		return $this->db->result();
	}

	public function spec($id, $p = false) {
		if ($id == 'next') {

			$this->db->query('SELECT `dp`.*, `s`.`id` `sid`, CONCAT(`s`.`s_first_name`, " ", `s`.`s_last_name`) `seller` FROM `draft_product` `dp` JOIN `seller` `s` ON (`dp`.`dp_sellerstamp` = `s`.`id`) WHERE `dp`.`dp_operatorstamp` IS NULL LIMIT 1');
			$ps = $this->db->single();
			
			if ($ps) {
				$this->db->query('UPDATE `draft_product` SET `dp_operatorstamp` = ' . $_SESSION[CLIENT . 'user_id']->id . ' WHERE `id` = ' . $ps->id);
				$this->db->execute();
				return $ps;
			} else {
				return [
					'card-tag' => [
						'type' => 'info',
						'body' => '<b>No more drafts</b>'
					]
				];
			}

		} elseif (preg_match('/^[0-9]+$/', $id)) {

			$this->db->query('SELECT `dp`.*, `s`.`id` `sid`, CONCAT(`s`.`s_first_name`, " ", `s`.`s_last_name`) `seller` FROM `draft_product` `dp` JOIN `seller` `s` ON (`dp`.`dp_sellerstamp` = `s`.`id`) WHERE `dp`.`id` = :id AND (`dp`.`dp_operatorstamp` IS NULL OR `dp`.`dp_operatorstamp` = ' . $_SESSION[CLIENT . 'user_id']->id . ')');
			$this->db->bind(':id', $id, $this->db->PARAM_INT);
			$ps = $this->db->single();
			
			if ($ps) {
				$this->db->query('UPDATE `draft_product` SET `dp_operatorstamp` = ' . $_SESSION[CLIENT . 'user_id']->id . ' WHERE `id` = ' . $ps->id);
				$this->db->execute();
				return $ps;
			} else {
				return [
					'card-tag' => [
						'type' => 'danger',
						'body' => '<b>Invalid ID</b>'
					]
				];
			}

		} else {
			
			$this->db->query('SELECT `dp`.*, `s`.`id` `sid`, CONCAT(`s`.`s_first_name`, " ", `s`.`s_last_name`) `seller` FROM `draft_product` `dp` JOIN `seller` `s` ON (`dp`.`dp_sellerstamp` = `s`.`id`) WHERE `dp`.`id` < :id AND `dp`.`dp_operatorstamp` = ' . $_SESSION[CLIENT . 'user_id']->id . ' LIMIT 1');
			$this->db->bind(':id', $p, $this->db->PARAM_INT);
			$ps = $this->db->single();
			
			if ($ps) {
				return $ps;
			} else {
				return [
					'card-tag' => [
						'type' => 'danger',
						'body' => '<b>No previous draft</b>'
					]
				];
			}

		}
	}

	public function approve($id) {
		$this->db->query('SELECT * FROM `draft_product` WHERE `id` = :id AND `dp_status` = 1 AND `dp_operatorstamp` = ' . $_SESSION[CLIENT . 'user_id']->id);
		$this->db->bind(':id', $id);
		$ap = $this->db->single();

		if (!$ap) {
			return;
		}
		
		$this->db->query('INSERT INTO `product` 
		(
			`p_name`, `p_handle`, `p_category`, `p_brand`, `p_model`, `p_description`, `p_price`, `p_category_spec`, `p_custom_field`, `p_variation`, `p_status`, `p_o_status`, `p_sellerstamp`, `p_operatorstamp`, `p_timestamp`, `p_latimestamp`
		)
		 VALUES 
		(
			"'.$ap->dp_name.'", "'.$ap->dp_handle.'", "'.$ap->dp_category.'", "'.$ap->dp_brand.'", "'.$ap->dp_model.'", "'.$ap->dp_description.'", "'.$ap->dp_price.'", "'.addslashes($ap->dp_category_spec).'", "'.addslashes($ap->dp_custom_field).'", "'.$ap->dp_variation.'", 1, 1, '.$ap->dp_sellerstamp.', '.$ap->dp_operatorstamp.', '.$ap->dp_timestamp.', '.$ap->dp_latimestamp.'
		)');
		
		$this->db->execute();
		$id = $this->db->lastInsertId();

		mkdir(DATADIR.DS.'product'.DS.$id);

		$imgs = glob(DATADIR.DS.'draft'.DS.$ap->id.DS.'*');
		foreach ($imgs as $img) {
			file_put_contents(DATADIR.DS.'product'.DS.$id.DS.basename($img), file_get_contents($img));
			unlink($img);
		}

		// remove folder
		rmdir(DATADIR.DS.'draft'.DS.$ap->id);

		$this->db->query('DELETE FROM `draft_product` WHERE `id` = ' . $ap->id);
		$this->db->execute();
	}
}

?>