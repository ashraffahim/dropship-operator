<?php

namespace models;

use libraries\Database;

class _Transaction {

	private $db;

	function __construct() {
		$this->db = new Database();
	}

	public function list($p) {
		$this->db->query('
			SELECT 
				* 
			FROM 
				`payment` 
			ORDER BY 
				`p_latimestamp` DESC 
			LIMIT 
				' . ($p - 1) * ROW_LIMIT . ', ' . ROW_LIMIT . '
		');

		return $this->db->result();
	}

}

?>